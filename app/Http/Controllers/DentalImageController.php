<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDentalImageRequest;
use App\Models\Appointment;
use App\Models\DentalImage;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\ToothRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class DentalImageController extends Controller
{
    public function index(Patient $patient, Request $request): View
    {
        $query = $patient->dentalImages()->with(['dentist', 'appointment']);

        // Filter by image type
        if ($request->filled('image_type')) {
            $query->where('image_type', $request->image_type);
        }

        // Filter by tooth number
        if ($request->filled('tooth_number')) {
            $query->where('tooth_number', $request->tooth_number);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('captured_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('captured_date', '<=', $request->date_to);
        }

        // Search in title and description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('clinical_notes', 'like', "%{$search}%");
            });
        }

        $images = $query->orderBy('captured_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dental-images.index', compact('patient', 'images'));
    }

    public function create(Patient $patient): View
    {
        $dentists = Dentist::all();
        $appointments = Appointment::where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->get();
        $toothRecords = ToothRecord::where('patient_id', $patient->id)
            ->orderBy('treatment_date', 'desc')
            ->get();

        return view('dental-images.create', compact('patient', 'dentists', 'appointments', 'toothRecords'));
    }

    public function store(StoreDentalImageRequest $request, Patient $patient): RedirectResponse
    {
        $uploadedImages = [];

        foreach ($request->file('images') as $file) {
            try {
                $uploadedImages[] = $this->processAndStoreImage($file, $patient, $request->validated());
            } catch (\Exception $e) {
                // Log error and continue with other images
                logger()->error('Failed to upload image: '.$e->getMessage());
            }
        }

        if (count($uploadedImages) === 0) {
            return back()->with('error', 'Failed to upload images. Please try again.');
        }

        $message = count($uploadedImages) === 1
            ? 'Image uploaded successfully.'
            : count($uploadedImages).' images uploaded successfully.';

        return redirect()
            ->route('patients.images.index', $patient)
            ->with('success', $message);
    }

    public function show(Patient $patient, DentalImage $image): View
    {
        if ($image->patient_id !== $patient->id) {
            abort(404);
        }

        $image->load(['dentist', 'appointment', 'toothRecord', 'relatedImage']);

        return view('dental-images.show', compact('patient', 'image'));
    }

    public function download(Patient $patient, DentalImage $image): Response
    {
        if ($image->patient_id !== $patient->id) {
            abort(404);
        }

        if (! Storage::disk('dental_images')->exists($image->file_path)) {
            abort(404, 'Image file not found');
        }

        return response()->file(
            Storage::disk('dental_images')->path($image->file_path),
            ['Content-Type' => $image->mime_type]
        );
    }

    public function thumbnail(Patient $patient, DentalImage $image): Response
    {
        if ($image->patient_id !== $patient->id) {
            abort(404);
        }

        $path = $image->thumbnail_path ?? $image->file_path;

        if (! Storage::disk('dental_images')->exists($path)) {
            abort(404, 'Thumbnail not found');
        }

        return response()->file(
            Storage::disk('dental_images')->path($path),
            ['Content-Type' => 'image/jpeg']
        );
    }

    public function destroy(Patient $patient, DentalImage $image): RedirectResponse
    {
        if ($image->patient_id !== $patient->id) {
            abort(404);
        }

        $image->delete();

        return redirect()
            ->route('patients.images.index', $patient)
            ->with('success', 'Image deleted successfully.');
    }

    protected function processAndStoreImage($file, Patient $patient, array $data): DentalImage
    {
        $tenantId = auth()->user()->tenant_id;

        // Generate unique filename
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid().'_'.time().'.'.$extension;

        // Store path structure: tenant_id/patient_id/originals/filename
        $storagePath = "{$tenantId}/{$patient->id}/originals/{$filename}";

        // Store original image
        Storage::disk('dental_images')->put($storagePath, file_get_contents($file));

        // Create thumbnail
        $thumbnailPath = $this->createThumbnail($file, $tenantId, $patient->id, $filename);

        // Get image dimensions
        [$width, $height] = getimagesize($file);

        // Create database record
        return DentalImage::create([
            'tenant_id' => $tenantId,
            'patient_id' => $patient->id,
            'tooth_record_id' => $data['tooth_record_id'] ?? null,
            'tooth_number' => $data['tooth_number'] ?? null,
            'title' => $data['title'] ?? null,
            'description' => $data['description'] ?? null,
            'image_type' => $data['image_type'],
            'file_path' => $storagePath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'thumbnail_path' => $thumbnailPath,
            'width' => $width,
            'height' => $height,
            'view_angle' => $data['view_angle'] ?? null,
            'appointment_id' => $data['appointment_id'] ?? null,
            'dentist_id' => $data['dentist_id'] ?? null,
            'captured_date' => $data['captured_date'] ?? now(),
            'clinical_notes' => $data['clinical_notes'] ?? null,
            'category' => $data['category'] ?? null,
            'tags' => $data['tags'] ?? null,
            'is_before_photo' => $data['is_before_photo'] ?? false,
            'is_after_photo' => $data['is_after_photo'] ?? false,
            'related_image_id' => $data['related_image_id'] ?? null,
        ]);
    }

    protected function createThumbnail($file, int $tenantId, int $patientId, string $filename): string
    {
        $manager = new ImageManager(new Driver);

        // Generate thumbnail
        $thumbnail = $manager->read($file);
        $thumbnail->scale(width: 300);

        // Thumbnail path
        $thumbnailFilename = 'thumb_'.$filename;
        $thumbnailPath = "{$tenantId}/{$patientId}/thumbnails/{$thumbnailFilename}";

        // Store thumbnail
        Storage::disk('dental_images')->put(
            $thumbnailPath,
            $thumbnail->toJpeg(quality: 85)->toString()
        );

        return $thumbnailPath;
    }
}
