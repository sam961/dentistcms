# Feature Screenshots Guide - Multi-Image Support

## 📸 Naming Convention

The system now supports **multiple screenshots per feature**!

### For Single Screenshot:
Simply name your file: `feature-name.png`

Example:
```
patients.png
```

### For Multiple Screenshots:
Use numbered suffixes: `feature-name-1.png`, `feature-name-2.png`, `feature-name-3.png`, etc.

Examples:
```
patients-1.png
patients-2.png
patients-3.png
```

---

## 📋 Required Screenshot Filenames

### 1. **Patient Management**
- Single: `patients.png` ✅ (Already exists)
- Multiple: `patients-1.png`, `patients-2.png`, `patients-3.png`, etc.

### 2. **Smart Appointment Scheduling**
- Single: `appointments.png` ✅ (Already exists)
- Multiple: `appointments-1.png`, `appointments-2.png`, `appointments-3.png`, etc.

### 3. **Dentist & Staff Management**
- Single: `dentists.png`
- Multiple: `dentists-1.png`, `dentists-2.png`, `dentists-3.png`, etc.

### 4. **Treatment Plans & Procedures**
- Single: `treatment-plans.png`
- Multiple: `treatment-plans-1.png`, `treatment-plans-2.png`, `treatment-plans-3.png`, etc.

### 5. **Digital Dental Charts**
- Single: `dental-chart.png`
- Multiple: `dental-chart-1.png`, `dental-chart-2.png`, `dental-chart-3.png`, etc.

### 6. **Invoicing & Billing**
- Single: `invoices.png`
- Multiple: `invoices-1.png`, `invoices-2.png`, `invoices-3.png`, etc.

### 7. **Medical Records & History**
- Single: `medical-records.png`
- Multiple: `medical-records-1.png`, `medical-records-2.png`, `medical-records-3.png`, etc.

### 8. **Reports & Analytics Dashboard**
- Single: `dashboard.png`
- Multiple: `dashboard-1.png`, `dashboard-2.png`, `dashboard-3.png`, etc.

### 9. **Notifications & Reminders**
- Single: `notifications.png`
- Multiple: `notifications-1.png`, `notifications-2.png`, `notifications-3.png`, etc.

---

## ✨ How It Works

### Detection System:
The system automatically detects both single and multiple images:
1. First checks for single image: `feature-name.png`
2. Then checks for numbered images: `feature-name-1.png`, `feature-name-2.png`, etc.
3. Combines all found images into a gallery

### Button Display:
- **Single image**: Button shows "Click to Preview Screenshot"
- **Multiple images**: Button shows "Click to Preview 3 Screenshots" (count updates automatically)

### Gallery Navigation:
- **Previous/Next Buttons**: Left/Right arrows appear when multiple images exist
- **Keyboard Navigation**: Use ← and → arrow keys to navigate
- **Counter Display**: Shows "Image 2/5" in the title
- **Smooth Transitions**: Images fade in/out when navigating

---

## 💡 Best Practices

### Image Naming:
✅ **DO**: `patients-1.png`, `patients-2.png`
❌ **DON'T**: `patients_1.png`, `patients1.png`, `patient-1.png`

### File Format:
- Use **PNG** format only
- Screenshots should be **1920x1080** or similar resolution
- Remove browser chrome (show only application content)
- Blur any sensitive patient data if needed

### Numbering:
- Start from `1` (not `0`)
- Use sequential numbers: `1, 2, 3, 4...`
- No gaps: Don't skip numbers

### Image Order:
Images will display in numerical order:
1. `feature-name-1.png` = First image
2. `feature-name-2.png` = Second image
3. `feature-name-3.png` = Third image
... and so on

---

## 🎯 Example Setup

For **Patient Management** feature with 4 screenshots:

```
public/images/features/
├── patients-1.png    # Patient list view
├── patients-2.png    # Patient creation form
├── patients-3.png    # Patient profile details
└── patients-4.png    # Patient medical history
```

Button will show: "Click to Preview 4 Screenshots"

User can navigate through all 4 images using arrows or keyboard.

---

## 🚀 Quick Start

1. Take your screenshots
2. Name them according to the convention above
3. Save to `public/images/features/`
4. Refresh the landing page
5. The gallery will work automatically!

No code changes needed! 🎉
