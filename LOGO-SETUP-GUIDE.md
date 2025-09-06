# 🎨 Logo Setup Guide - LGU1 Portal

## 📧 Email Template Logo Replacement

Your email templates now include a professional SVG logo placeholder. To use your actual LGU logo, follow these simple steps:

---

## 🔧 Option 1: Replace with Image File

### Step 1: Add Your Logo
1. Place your logo file in `public/images/` directory
2. Recommended formats: PNG, SVG, or JPEG
3. Recommended size: 64x64px or 128x128px

### Step 2: Update Email Templates
Replace the SVG logo section in both files:
- `resources/views/emails/email-verification.blade.php`
- `resources/views/emails/registration-verification.blade.php`

**Find this section:**
```html
<div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: 50%; margin-right: 15px;">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- SVG content -->
    </svg>
</div>
```

**Replace with:**
```html
<div style="margin-right: 15px;">
    <img src="{{ asset('images/lgu-logo.png') }}" 
         alt="LGU1 Logo" 
         width="48" 
         height="48" 
         style="border-radius: 8px;">
</div>
```

---

## 🔧 Option 2: Use Base64 Encoded Image

### Step 1: Convert Logo to Base64
1. Visit: https://base64.guru/converter/encode/image
2. Upload your logo image
3. Copy the base64 string

### Step 2: Update Templates
Replace the SVG with:
```html
<div style="margin-right: 15px;">
    <img src="data:image/png;base64,YOUR_BASE64_STRING_HERE" 
         alt="LGU1 Logo" 
         width="48" 
         height="48" 
         style="border-radius: 8px;">
</div>
```

---

## 🎯 Current Logo Features

✅ **Professional Design**: Clean government building representation  
✅ **Email Compatible**: Works in all email clients  
✅ **Consistent Branding**: Same logo in both email templates  
✅ **Responsive**: Scales properly on mobile devices  

---

## 📝 Notes

- The current SVG logo represents a government building
- Logo appears in both registration and standard verification emails  
- Logo has subtle transparency effect that works with gradient background
- Easy to replace with your official LGU logo when available

---

**Need help?** The current SVG logo is professional and functional until you're ready to add your official logo.
