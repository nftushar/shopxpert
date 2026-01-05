Summary of fixes applied (2026-01-05):

1) Fixed settings fields not saving
   - Root cause: many form inputs used incorrect `name` attributes (e.g., `name="option"` instead of `name="section[option]"`), so serialized form data did not map into the expected section arrays.
   - Fix: Updated `incs/admin/inc/Shopxpert_Admin_Fields_Manager.php` to ensure all inputs use `name="<section>[<option>]"` (text, textarea, select, checkboxes, number, color, multiselect, repeaters, nested fields etc.).

2) Added a minimal `serializeJSON` polyfill
   - Implemented `incs/admin/assets/js/jquery.serializejson.js` and registered it under the `serializejson` handle so `serializeJSON()` is always available.

3) Resolved wishlist template fatal error
   - Problem: `wishlist-table.php` expected `$wishlist` variable to exist; some calls passed `shopxpert` key instead.
   - Fix: Made `WishList_get_template()` set `$wishlist = $shopxpert` when present, and made `wishlist-table.php` defensive (fallback to `Manage_Wishlist::instance()` and ensure `$fields` is an array).

4) Hardening
   - `get_option()` in `Shopxpert_Admin_Fields_Manager` now ensures the section option is an array before indexing to avoid PHP notices.
   - Minor JS and PHP linting ran on changed files.

How to verify (quick):
 - Open WP admin -> Shopxpert -> any settings tab
 - Change some fields (checkbox/text/select) and click "Save Changes"
 - The Save button should show "Saved" and the options should persist after page reload
 - Check Logs: `logs/php/error.log` should not show the previous wishlist fatal error

Files changed:
 - incs/admin/inc/Shopxpert_Admin_Fields_Manager.php (multiple name fixes, get_option hardening)
 - incs/admin/assets/js/jquery.serializejson.js (new)
 - classes/class.assest_management.php (register serializejson)
 - incs/features/wishlist/incs/helper-functions.php (set $wishlist fallback)
 - incs/features/wishlist/incs/templates/wishlist-table.php (defensive checks)

If you want, I can:
 - Add unit-style integration tests for serialization and AJAX save handler
 - Run an automated pass to find other templates relying on non-present variables and add fallbacks
 - Walk through a manual verification checklist on your local site and log results

Next step: finalize documentation and add simple integration test(s).