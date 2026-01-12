# User Guide: Quotation Create Button Feature

## 📍 Where to Find the Create Button

The new "Create" buttons appear in the **Leads Management Table** in two columns:

### Location 1: QUOTATIONS Column
- **Column Header:** "QUOTATIONS"
- **When it appears:** When a lead has NO existing quotations
- **What it replaces:** Previously showed "None" in gray text
- **Visual Appearance:** Blue button with plus (+) icon

### Location 2: REVISED QUOTATIONS Column  
- **Column Header:** "REVISED QUOTATIONS"
- **When it appears:** When a lead has NO revised versions
- **What it replaces:** Previously showed "None" in gray text
- **Visual Appearance:** Blue button with plus (+) icon

---

## 🎯 How to Use the Create Button

### Step 1: Access Leads Management
```
Menu → Leads Management → All Leads
```

### Step 2: Find a Lead Without Quotation
Scroll through the table and look for:
- A lead row where the QUOTATIONS column shows a blue "Create" button
- (Instead of a quotation count or "None" text)

### Step 3: Click the Create Button
- Click on the blue "Create" button
- You'll be taken to the Quotation Creation form

### Step 4: Verify Pre-selection
The Client/Lead field will automatically be pre-selected:
```
Example: "Amit Singh - Solar Company Ltd"
```

### Step 5: Complete the Quotation
- Fill in the quotation details
- Submit the form to create the quotation

---

## 💡 Tips & Tricks

### ⚡ Speed Up Your Workflow
- The pre-selected client saves you from manually selecting each time
- Perfect for creating multiple quotations for different leads

### 🔄 Change Client if Needed
- If you clicked the wrong lead's button, you can still change the client
- The dropdown is editable even with pre-selection

### 📱 Mobile Usage
- The button works on tablets and phones
- Touch-friendly size for easy clicking

### 🔍 Finding Leads Without Quotations
- Use filters to find "Not Set" or empty quotation status
- Helps identify leads needing quotations

---

## 🎨 Visual Reference

### Button Appearance:
```
┌─────────────┐
│  ➕ Create  │  ← Blue button with plus icon
└─────────────┘
```

**On Hover:**
- Button background becomes slightly darker blue
- Indicates it's clickable

### Before & After Comparison:

**BEFORE:**
```
QUOTATIONS Column:
├─ Lead 1 → "1 Quotation" (badge)
├─ Lead 2 → "None" (gray text)
└─ Lead 3 → "None" (gray text)
```

**AFTER:**
```
QUOTATIONS Column:
├─ Lead 1 → "1 Quotation" (badge)
├─ Lead 2 → [➕ Create] (clickable button)
└─ Lead 3 → [➕ Create] (clickable button)
```

---

## 👥 For All User Roles

### SUPER ADMIN
- ✅ Full access to all leads
- ✅ Can create quotations for any lead
- ✅ Pre-selection works for all leads

### SALES MANAGER
- ✅ Can view and manage leads
- ✅ Can create quotations with pre-selection
- ✅ Workflow is streamlined

### TELE SALES
- ✅ Can view assigned leads
- ✅ Pre-selection speeds up calling workflow
- ✅ Quickly generate quotations during calls

### FIELD SALES
- ✅ Can access assigned leads
- ✅ Can create quotations on-site
- ✅ Client pre-selection reduces typing

---

## 🆘 Troubleshooting

### Q: The button doesn't appear for a lead I know has no quotations
**A:** 
1. Refresh the page (F5)
2. Clear cache (Ctrl+Shift+Delete)
3. Check if the lead actually has quotations linked to it

### Q: The client field is not pre-selected when I click the button
**A:**
1. Check the URL shows: `/quotations/create?client_id=X`
2. If URL is correct, try refreshing the page
3. Contact IT support if issue persists

### Q: Can I still manually select a different client?
**A:** 
**YES!** The pre-selection is just a default. You can click the dropdown and select any other client you need.

### Q: What if I accidentally clicked the wrong lead?
**A:** 
**No problem!** You can change the client in the dropdown before submitting the form.

---

## 📝 Example Workflow

### Scenario: Creating a quotation for Amit Singh from the Leads list

1. **Login & Navigate**
   ```
   Email: sales.manager@solarerp.com
   Password: password123
   → Go to Leads Management
   ```

2. **Find Amit Singh's Lead**
   ```
   Leads Table → Look for row: "Amit Singh"
   ```

3. **Check Quotations Column**
   ```
   If it shows: "None" or is empty
   Then you see: [➕ Create] button
   ```

4. **Click Create Button**
   ```
   Click the blue [➕ Create] button
   ```

5. **Verify Pre-selection**
   ```
   Form appears → Client field shows: "Amit Singh - His Company"
   ```

6. **Fill in Details**
   ```
   • Quotation Type: Select type
   • Valid Until: Set date
   • Items: Add items
   • Amount: Enter amount
   ```

7. **Submit**
   ```
   Click "Create Quotation" button
   → Quotation is created!
   ```

---

## ✨ Key Benefits

✅ **Faster Quotation Creation**
- No need to manually find and select each lead
- Pre-filled data saves 2-3 seconds per quotation

✅ **Fewer Mistakes**
- Automatic selection prevents selecting wrong client
- Visual confirmation of client before submitting

✅ **Better Workflow**
- From leads list → directly to quotation form
- Seamless transition

✅ **Mobile Friendly**
- Works great on tablets and smartphones
- Easy to use on the go

---

## 📞 Support

If you encounter any issues:
1. Check troubleshooting section above
2. Clear cache and refresh browser
3. Try a different browser
4. Contact IT Support with screenshot

---

## Version Information

- **Feature:** Quotation Create Button
- **Version:** 1.0
- **Release Date:** January 12, 2026
- **Compatibility:** All user roles
- **Browsers:** Chrome, Firefox, Safari, Edge (latest versions)

**Status:** ✅ Production Ready
