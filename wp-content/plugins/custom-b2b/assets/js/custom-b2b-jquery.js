/*
* Override b2b plugin add to cart ajax + pass extra attributes data to cart/checkout
*/
/*jQuery(document).ajaxSend(function (event, xhr, settings) {
    if (settings.data && settings.data.includes('action=b2bking_bulkorder_add_cart_item')) {

        // Extract productid from AJAX data
        const productIdMatch = settings.data.match(/productid=(\d+)/);
        const productId = productIdMatch ? productIdMatch[1] : null;
        if (!productId) return;

        const attributes = [];

        const container = jQuery('.cpa-product-fields[data-productid="' + productId + '"]');
        container.find('select, input[type="text"]').each(function() {
            let name = jQuery(this).attr('name');  // e.g. cpa_panel-width
            let value = jQuery(this).val();        // e.g. 15\’

            if (!value) return;

            // --- Clean and normalize name ---
            // Remove prefix (cpa_) and make it human readable
            name = name.replace(/^cpa_/, '');         // remove 'cpa_'
            name = name.replace(/[-_]+/g, ' ');       // replace _ and - with space
            name = name.replace(/\b\w/g, c => c.toUpperCase()); // capitalize each word

            // --- Clean value ---            
            value = value.replace(/["\\]+/g, '“').trim(); // Replace backslash or double quote with readable quote           

            // Build clean attribute string
            attributes.push(name + '=' + value);
        });

        // Remove any previously added attributes
        settings.data = settings.data.replace(/&attributes\[\]=[^&]*//*g, '');

        // Append new attributes
        attributes.forEach(attr => {
            settings.data += '&attributes[]=' + encodeURIComponent(attr);
        });
    }
});*/
// this code is the upgrated version of the above code for adding js valdation feature on add to cart
jQuery(function ($) {
    
     const observer = new MutationObserver(function () {

        $('.cpa-product-fields')
            .find('select[required], input[required]')
            .each(function () {

                $(this)
                    .attr('data-required', '1') // keep custom validation flag
                    .removeAttr('required');    // remove browser validation

            });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });


    const originalAjax = $.ajax;

    $.ajax = function (options) {

        let dataString = '';

        if (typeof options.data === 'string') {
            dataString = options.data;
        } else if (typeof options.data === 'object') {
            dataString = $.param(options.data);
        }

        const isAddToCart = dataString.includes('action=b2bking_bulkorder_add_cart_item');
        const isStockCheck = dataString.includes('action=b2bking_get_stock_quantity_addable');

        if (isAddToCart || isStockCheck) {

            const productIdMatch = dataString.match(/(productid|id)=(\d+)/);
            const productId = productIdMatch ? productIdMatch[2] : null;

            if (!productId) {
                return originalAjax.apply(this, arguments);
            }

            const container = $('.cpa-product-fields[data-productid="' + productId + '"]');
            if (!container.length) {
                return originalAjax.apply(this, arguments);
            }

            /* ===============================
               CUSTOM VALIDATION
            =============================== */

            let hasError = false;

            container.find('[data-required="1"]').each(function () {

                let $field = $(this);
                let value = $field.val();

                $field.removeClass('cpa-error');
                $field.next('.cpa-error-text').remove();

                if (!value || value.trim() === '') {
                    hasError = true;

                    $field.addClass('cpa-error');
                    $('<div class="cpa-error-text">This is required field!</div>')
                        .insertAfter($field);
                }
            });

            if (hasError) {

                alert('Please fill all required fields before adding to order.');

                const button = $('.b2bking_bulkorder_indigo_add, .b2bking_bulkorder_cream_add')
                    .filter(function () {
                        return $(this)
                            .closest('.b2bking_bulkorder_form_container_content_line')
                            .find('.cpa-product-fields')
                            .data('productid') == productId;
                    });

                button.html('Add more').prop('disabled', false);

                return $.Deferred().resolve({
                    success: false,
                    error: true
                }).promise();
            }

            /* ===============================
               ATTRIBUTE PASSING (RESTORED)
            =============================== */

            if (isAddToCart) {

                const attributes = [];

                container.find('select, input[type="text"]').each(function () {

                    let name = $(this).attr('name');
                    let value = $(this).val();

                    if (!value) return;

                    // Clean name
                    name = name.replace(/^cpa_/, '');
                    name = name.replace(/[-_]+/g, ' ');
                    name = name.replace(/\b\w/g, c => c.toUpperCase());

                    // Clean value
                    value = value.replace(/["\\]+/g, '“').trim();

                    attributes.push(name + '=' + value);
                });

                // Remove old attributes if exist
                options.data = dataString.replace(/&attributes\[\]=[^&]*/g, '');

                // Append new attributes
                attributes.forEach(attr => {
                    options.data += '&attributes[]=' + encodeURIComponent(attr);
                });


                /* =====================================
                    SUCCESS ALERT AFTER ADD TO CART
                ===================================== */

                const originalSuccess = options.success;

                options.success = function (response) {

                    // Run original B2BKing success
                    if (typeof originalSuccess === 'function') {
                        originalSuccess.apply(this, arguments);
                    }

                    // Show alert only if successful
                    if (response && response.success !== false) {
                        alert('Product added to cart successfully!');
                    }
                };

            }
        }

        return originalAjax.apply(this, arguments);
    };
    $(document).on('input change', '.cpa-product-fields [data-required="1"], .cpa-edit-form [data-required="1"]', function () {

        let $field = $(this);
        let value = $field.val();

        if ($field.is('select')) {
            if (value && value !== '') {
                $field.removeClass('cpa-error');
                $field.next('.cpa-error-text').remove();
            }
        } else {
            if (value && value.trim() !== '') {
                $field.removeClass('cpa-error');
                $field.next('.cpa-error-text').remove();
            }
        }
    });

});





/*
* Override b2b plugin bulk order ajax + pass extra attributes data to cart/checkout
*/
jQuery(document).ajaxSend(function (event, xhr, settings) {
    // Only modify bulk add requests
    if (settings.data && settings.data.includes('action=b2bking_bulkorder_add_multiple')) {

        try {
            // Extract additions JSON from the request
            const match = settings.data.match(/additions=([^&]*)/);
            if (!match) return;

            // Decode and parse the additions array
            const additions = JSON.parse(decodeURIComponent(match[1]));

            // Loop through each product addition
            additions.forEach(function(addition) {
                const productId = addition.productid;
                const container = jQuery('.part-item[data-productid="' + productId + '"]');

                if (!container.length) return;

                const attributes = [];

                container.find('select, input[type="text"]').each(function() {
                    let name = jQuery(this).attr('name');
                    let value = jQuery(this).val();

                    if (!value) return;

                    // Clean & normalize label
                    name = name.replace(/^cpa_/, '');
                    name = name.replace(/[-_]+/g, ' ');
                    name = name.replace(/\b\w/g, c => c.toUpperCase());

                    // Clean value (remove slashes)
                    if (typeof value === 'string') {
                        value = value
                            .replace(/\\(['"’“])/g, '$1') // unescape quotes
                            .replace(/\\+/g, '')          // remove stray backslashes
                            .trim();
                    }

                    attributes.push(name + '=' + value);
                });

                // Inject attributes into this product
                addition.attributes = attributes;
            });
            console.log(attributes);

            // Replace old JSON in the AJAX request
            const newAdditions = encodeURIComponent(JSON.stringify(additions));
            settings.data = settings.data.replace(/additions=([^&]*)/, 'additions=' + newAdditions);

            console.log('[Modified AJAX]', settings.data);

        } catch (err) {
            console.error('Error modifying b2bking_bulkorder_add_multiple:', err);
        }
    }
});





/*
* on click "Parts & Components" category menu, it fetch deafult part products
*/
jQuery('.parts-category-menu').on('click', function(e){
    e.preventDefault();      // stop default link or form behavior
    e.stopImmediatePropagation(); // stop other click handlers by B2B plugin

    jQuery('.finished-product-row').hide();
    jQuery('.part-product-loading').show();
    let clickedCategoryId = jQuery(this).attr('value'); 
    jQuery('.b2bking_bulkorder_filters_list li').css('text-decoration','none').css('color','#333'); 
    jQuery('.parts-category-menu').css('color','#333');      
    jQuery(this).css('color','#e10915');  

    jQuery.ajax({
      url: b2bking_display_settings.ajaxurl,
      type: 'POST',
      data: {
        action: 'b2bking_ajax_fetch_parts_product',
        category_id: clickedCategoryId
      },
      success: function(response) {    
        jQuery('.part-product-loading').hide();    
        jQuery('.finished-product-row').show();
        jQuery('.b2bking_bulkorder_form_container_content').html(response.data.html);
        jQuery('.b2bking_bulkorder_form_container_top_indigo.b2bking_bulkorder_form_container_top_cream').fadeOut(100);
      }
    });
    
});





/*
* On parts category dropdown change the parts list content will be changed with ajax call
*/
jQuery(document).ready(function($) {

    // When dropdown changes
    jQuery(document).on('change', '.parts-category-dropdown', function() {    

        const dropdown = $(this);
        const selectedCat = dropdown.val();
        const finishedProductId = dropdown.data('finished');

        // Find the container (finished product row)
        const container = dropdown.closest('.finished-product-row');
        
        // Optional loading indicator
        container.find('.parts-products').html('<p>Loading parts...</p>');

        // AJAX request to load products of selected parts category
        $.ajax({
          url: b2bking_display_settings.ajaxurl, // or your plugin localized var
          type: 'POST',
          data: {
            action: 'load_parts_products_by_category',
            category_id: selectedCat,
            finished_id: finishedProductId
          },
          success: function(response) {
            if (response.success) {
              // Replace the parts product list under this finished product
              container.find('.parts-products').html(response.data.html);
            } else {
              container.find('.parts-products').html('<p>No products found.</p>');
            }
          },
          error: function() {
            container.find('.parts-products').html('<p>Error loading products.</p>');
          }
        });

    });



    /*
    * Collect Checked Items and Send to AJAX for making order
    */
    jQuery(document).on('click', '#add-all-to-cart-btn', function (e) {
        e.preventDefault();

        const checkedItems = jQuery('.part-item input[type="checkbox"]:checked');

        if (checkedItems.length === 0) {
            alert('Please select at least one item.');
            return;
        }

        let additions = [];
        let invalidQty = false;

        checkedItems.each(function () { 
            const row = jQuery(this).closest('.part-box-right');
            const productId = jQuery(this).val();
            const quantity = parseInt(row.find('input.b2bking_bulkorder_form_container_content_line_qty').val() || '0');            

            // If quantity is less than 1, mark invalid
            if (isNaN(quantity) || quantity < 1) {
                invalidQty = true;
                row.find('input.b2bking_bulkorder_form_container_content_line_qty').addClass('qty-error');
                return; // skip this item
            } else {
                row.find('input.b2bking_bulkorder_form_container_content_line_qty').removeClass('qty-error');
            }



            // Fake jQuery-like structure for B2BKing compatibility
            const fakeButton = {
                0: {},
                length: 1,
                prevObject: {
                    0: {},
                    length: 1,
                    prevObject: {
                        0: {},
                        length: 1,
                        prevObject: {
                            0: {},
                            length: 1,
                            prevObject: { 0: {}, length: 1 }
                        }
                    }
                }
            };

            
            //get attributes data    
            const container = jQuery('.part-item[data-productid="' + productId + '"]');

            //if (!container.length) return;            
            const part_attributes = [];

            container.find('select, input[type="text"]').each(function() {
                let name = jQuery(this).attr('name');
                let value = jQuery(this).val();

                if (!value) return;

                // Clean & normalize label
                name = name.replace(/^cpa_/, '');
                name = name.replace(/[-_]+/g, ' ');
                name = name.replace(/\b\w/g, c => c.toUpperCase());

                // Clean value (remove slashes)
                if (typeof value === 'string') {
                    value = value
                        .replace(/\\(['"’“])/g, '$1') // unescape quotes
                        .replace(/\\+/g, '')          // remove stray backslashes
                        .trim();
                }

                part_attributes.push(name + '=' + value);
            });  
            //console.log(part_attributes);

            additions.push({
                action: 'b2bking_bulkorder_add_cart_item',
                security: b2bking_display_settings.security,
                productid: String(productId),
                productqty: String(quantity),
                attributes: part_attributes,
                customdata: [],
                qtyaddable: 999999999,
                thisbutton: fakeButton
            });
        });    

        // Stop if any invalid quantity found
        if (invalidQty) {
            alert('Please enter quantity 1 or more for all selected products.');
            return;
        }

        jQuery.ajax({
            url: b2bking_display_settings.ajaxurl,
            method: 'POST',

            // Remove enforced JSON parsing
            dataType: 'text', 

            data: {
                action: 'b2bking_bulkorder_add_multiple',
                security: b2bking_display_settings.security,
                additions: JSON.stringify(additions)
            },

            beforeSend: function () {
                jQuery('#add-all-to-cart-btn').prop('disabled', true).text('Adding...');
            },

            success: function (responseText) { 
                let response;
                try {
                    // Attempt JSON parse if possible
                    response = JSON.parse(responseText);
                } catch (e) {
                    response = { success: responseText.includes('success') || responseText.includes('added') };
                }

                if (
                    (response && response.success) || 
                    /woocommerce-Price-amount|added to your cart|cart/i.test(responseText)
                ) {
                    jQuery('.b2bking_orderform_checkout').removeClass('b2bking_orderform_checkout_inactive');
                    alert('Selected products added to your cart successfully!');
                } else {
                    console.warn('Unexpected B2BKing response:', responseText);
                    alert('Something went wrong: ' + (responseText || 'Unknown error'));
                }

            },

            complete: function () {
                jQuery('#add-all-to-cart-btn').prop('disabled', false).text('Add all selected items to Order');
            },

            error: function (xhr, status, error) {
                console.error('AJAX error:', error, xhr.responseText);
                alert('Server error. Please try again.');
            }
        });
    });




    /*
    * Quantity increase/decrease main action
    */
    (function() {
          // Utility to find closest ancestor with selector
          function closest(el, selector) {
            while (el && el !== document) {
              if (el.matches && el.matches(selector)) return el;
              el = el.parentNode;
            }
            return null;
          }

          // Parse number with step support (floats supported)
          function parseNumber(val, defaultVal) {
            if (val === '' || val === null || val === undefined) return defaultVal;
            var n = Number(val);
            return isNaN(n) ? defaultVal : n;
          }

          // Clamp value between min and max
          function clamp(val, min, max) {
            if (min !== null && val < min) return min;
            if (max !== null && val > max) return max;
            return val;
          }

          // Build handler that updates the input value (increment or decrement)
          function changeQty(inputEl, delta) {
            var stepAttr = inputEl.getAttribute('step');
            var step = stepAttr ? parseFloat(stepAttr) : 1;
            if (isNaN(step) || step <= 0) step = 1;

            var minAttr = inputEl.getAttribute('min');
            var maxAttr = inputEl.getAttribute('max');
            var min = (minAttr !== null && minAttr !== '') ? parseNumber(minAttr, null) : null;
            var max = (maxAttr !== null && maxAttr !== '') ? parseNumber(maxAttr, null) : null;

            // Use precise math for floats
            var current = parseNumber(inputEl.value, 0);
            var newVal = current + (delta * step);

            // normalize precision to step decimal places
            var decimals = (step.toString().split('.')[1] || '').length;
            if (decimals > 0) {
              var factor = Math.pow(10, decimals);
              newVal = Math.round(newVal * factor) / factor;
            }

            newVal = clamp(newVal, min, max);

            inputEl.value = String(newVal);
            // Fire change/input events so other code can listen
            var evt = new Event('input', { bubbles: true });
            inputEl.dispatchEvent(evt);
            var evt2 = new Event('change', { bubbles: true });
            inputEl.dispatchEvent(evt2);
          }

          // Very early capture-phase click handler to intercept plus/minus clicks
          document.addEventListener('click', function(e) {
            var target = e.target;

            // if clicked a minus button (or inside it)
            var minus = closest(target, '.b2bking_cream_input_minus_button');
            if (minus) {
              // stop plugin handlers running
              e.stopImmediatePropagation && e.stopImmediatePropagation();
              e.preventDefault();

              // find input
              var wrapper = closest(minus, '.b2bking_cream_input_group');
              if (!wrapper) return;
              var input = wrapper.querySelector('input.b2bking_bulkorder_form_container_content_line_qty');
              if (!input) return;

              changeQty(input, -1);
              return;
            }

            // if clicked a plus button (or inside it)
            var plus = closest(target, '.b2bking_cream_input_plus_button');
            if (plus) {
              e.stopImmediatePropagation && e.stopImmediatePropagation();
              e.preventDefault();

              var wrapper2 = closest(plus, '.b2bking_cream_input_group');
              if (!wrapper2) return;
              var input2 = wrapper2.querySelector('input.b2bking_bulkorder_form_container_content_line_qty');
              if (!input2) return;

              changeQty(input2, 1);
              return;
            }

            // not a button we care about
          }, true); // <--- capture phase (true) so we run before plugin's bubble handlers


          // Intercept direct manual typing into input to normalize and prevent plugin crash
          document.addEventListener('input', function(e) {
            var input = e.target;
            if (!input || !input.classList) return;
            if (!input.classList.contains('b2bking_bulkorder_form_container_content_line_qty')) return;

            // Prevent plugin handlers
            e.stopImmediatePropagation && e.stopImmediatePropagation();

            // Validate and clamp value
            var minAttr = input.getAttribute('min');
            var maxAttr = input.getAttribute('max');
            var min = (minAttr !== null && minAttr !== '') ? parseNumber(minAttr, null) : null;
            var max = (maxAttr !== null && maxAttr !== '') ? parseNumber(maxAttr, null) : null;
            var stepAttr = input.getAttribute('step');
            var step = stepAttr ? parseFloat(stepAttr) : 1;
            if (isNaN(step) || step <= 0) step = 1;

            var val = input.value;
            // allow empty while typing
            if (val === '') return;

            var n = parseNumber(val, 0);
            n = clamp(n, min, max);

            // normalize precision
            var decimals = (step.toString().split('.')[1] || '').length;
            if (decimals > 0) {
              var factor = Math.pow(10, decimals);
              n = Math.round(n * factor) / factor;
            }

            if (String(n) !== input.value) {
              input.value = String(n);
              // fire change event
              var evt = new Event('change', { bubbles: true });
              input.dispatchEvent(evt);
            }
          }, true); // capture

          // also intercept keydown on input to handle arrow keys quickly
          document.addEventListener('keydown', function(e) {
            var input = e.target;
            if (!input || !input.classList) return;
            if (!input.classList.contains('b2bking_bulkorder_form_container_content_line_qty')) return;

            // prevent plugin handler
            e.stopImmediatePropagation && e.stopImmediatePropagation();

            if (e.key === 'ArrowUp') {
              e.preventDefault();
              changeQty(input, 1);
            } else if (e.key === 'ArrowDown') {
              e.preventDefault();
              changeQty(input, -1);
            }
          }, true);

          // OPTIONAL: disable plugin's plus/minus if you prefer (may be re-bound later though)
          if (window.jQuery) {
            try { 
              // remove delegated handlers that jQuery may have set up on document for these selectors
              jQuery(document).off('click', '.b2bking_cream_input_plus_button');
              jQuery(document).off('click', '.b2bking_cream_input_minus_button');
            } catch (err) {
              // ignore
            }
          }
    })();

});



/*
* Automatically trigger a click to First Finished Product link from the list on left site panel
* on Bulk order page to load a default finished product details on right side
*/
document.addEventListener("DOMContentLoaded", function() {
    const interval = setInterval(() => {
        const li = document.querySelector('.b2bking_bulkorder_filters_list .first-finished-product');
        if (li) {
            li.click();            
            clearInterval(interval);
        }
    }, 500); // check every 0.5s
});




/*
* Display the toopltip beside product attributes 
* for first time on choosing or inputing attribute values
*/
document.addEventListener('DOMContentLoaded', function() {
    // Select all input/select fields inside the .cpa-product-fields area
    const fields = document.querySelectorAll('.cpa-product-fields input, .cpa-product-fields select');

    fields.forEach(function(field) {
        // Find the closest tooltip icon related to this field
        const icon = field.closest('p')?.querySelector('.cpa-info-icon');
        if (!icon) return;

        let tooltipShown = false; // flag to show tooltip only once

        // On first focus, show tooltip briefly
        field.addEventListener('focus', function() {
            if (tooltipShown) return; // skip if already shown

            icon.classList.add('show-tooltip');
            tooltipShown = true;

            // Hide after 2.5 seconds
            setTimeout(function() {
                icon.classList.remove('show-tooltip');
            }, 2500);
        });
    });
});




/*
* Open popup on clicking attributes link text
*/
document.addEventListener('DOMContentLoaded', function() {
  function initPopupElements() {
    const overlay = document.querySelector('.cpa-popup-overlay');
    if (!overlay) return;

    const popupContent = overlay.querySelector('.cpa-popup-content');
    const closeBtn = overlay.querySelector('.cpa-popup-close');

    document.querySelectorAll('.cpa-popup-trigger').forEach(btn => {
      btn.addEventListener('click', function() {
        popupContent.innerHTML = this.getAttribute('data-content');
        overlay.style.display = 'flex';
      });
    });

    closeBtn?.addEventListener('click', () => overlay.style.display = 'none');
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.style.display = 'none';
    });
  }

  // Initial run
  initPopupElements();

  // Watch for dynamically added elements
  const observer = new MutationObserver(initPopupElements);
  observer.observe(document.body, { childList: true, subtree: true });
});





/*
* Attributes condition logic
*/

 document.addEventListener("DOMContentLoaded", function () {
  //console.log("CPA: condition logic initialized");

  // Observe DOM for AJAX insertions
  const observer = new MutationObserver((mutations) => {
    mutations.forEach((m) => {
      m.addedNodes.forEach((node) => {
        if (!(node instanceof HTMLElement)) return;

        // Handle directly appended .cpa-product-fields
        if (node.classList.contains("cpa-product-fields")) {
          //console.log("CPA: new .cpa-product-fields detected");
          initCPAConditions(node);
        }

        // Handle if nested inside other container
        const innerBlocks = node.querySelectorAll(".cpa-product-fields");
        innerBlocks.forEach((block) => {
          //console.log("CPA: nested .cpa-product-fields detected");
          initCPAConditions(block);
        });
      });
    });
  });

  observer.observe(document.body, { childList: true, subtree: true });

  // Run on page load too (for product details page)
  document.querySelectorAll(".cpa-product-fields").forEach(initCPAConditions);
});

function initCPAConditions(container) {  
  const  partproduct_id = $(container).attr('data-productid');  
  
  let jsonEl = null; 
  if(partproduct_id){
    jsonEl = document.querySelector(".part_cpa_attribute_conditions_"+partproduct_id);    
  }
  
  //if (!jsonEl) return;
  // Now jsonEl ALWAYS exists (or is null)
  if (!jsonEl) {
    console.warn("CPA: JSON script not found for product:", partproduct_id);
    return;
  }

  let conditions = {};

  try {
    conditions = JSON.parse(jsonEl.textContent || "{}");
    //console.log("CPA: Conditions loaded", conditions);
  } catch (err) {
    console.warn("CPA: Invalid JSON in conditions", err);
    return;
  }

  // Bind change events inside this container
  container.querySelectorAll("select, input").forEach((el) => { 
    el.addEventListener("change", () => handleConditions(container, conditions));
  });

  // Evaluate once initially
  handleConditions(container, conditions);
}

function handleConditions(container, conditions) {
  Object.keys(conditions).forEach((attr) => {
    const cfg = conditions[attr];
    const field = container.querySelector(`[data-attribute="${attr}"]`);
    if (!field) return;

    // --- Show/Hide logic ---
    if (cfg.depends_on) {
      const depField = container.querySelector(
        `[data-attribute="${cfg.depends_on}"] select`
      );
      
      if (depField) {
        const selectedVal = depField.value.trim().toLowerCase();
        const showWhen = (cfg.show_when || []).map((v) => v.trim().toLowerCase());
        const hideWhen = (cfg.hide_when || []).map((v) => v.trim().toLowerCase());

        let shouldShow = true;
        if (showWhen.length && !showWhen.includes(selectedVal)) shouldShow = false;
        if (hideWhen.length && hideWhen.includes(selectedVal)) shouldShow = false;

        //field.style.display = shouldShow ? "block" : "none";
        if (shouldShow) {
            field.classList.add("show-field");
            field.classList.remove("hide-field");
        } else {
            field.classList.add("hide-field");
            field.classList.remove("show-field");
        }
        // ENABLE / DISABLE inputs + selects inside this field
        const inputs = field.querySelectorAll("input, select, textarea, button");

        inputs.forEach((el) => {
          el.disabled = !shouldShow;
        });

        

      }
    }

    // --- Formula / auto-fill logic ---    
    if (cfg.formula) {
      try {
        let calc = cfg.formula;        

        const vars = calc.match(/[a-zA-Z0-9_]+/g) || [];

        vars.forEach((v) => {
          const valEl = container.querySelector(
            `[data-attribute="${v}"] input, [data-attribute="${v}"] select`
          );
          if (!valEl) return;

          const raw = valEl.value;
          const val = parseFloat(raw);

          if (isNaN(val)) return;          
          calc = calc.replaceAll(v, Number(val).toString()); //safer for whitespace or accidental commas
        });

        calc = calc.replace(/\b0+(\d+)/g, '$1');
        //console.log("Original Formula:", cfg.formula);
        //console.log("Variables Found:", vars);
        //console.log("Final Formula:", calc);

        // skip if unresolved variables remain
        if (/[a-zA-Z_]/.test(calc)) {
          return;
        }

        const result = Function(`"use strict";return(${calc})`)();
        const output = Number(result.toFixed(3));
        const target = field.querySelector("input");
        if (target) target.value = isNaN(output) ? "" : output;        
      } catch (err) {
        console.warn("CPA: formula error", err);
      }
    }
  });
}





/*
* Add product id to "b2bking_ajax_search" Ajax data to get the product id as POST data 
* on clicking the Finished Product name on left side menu on Bulk Order Page
* It will help to show single product on right side
*/

let selectedProductID = null;

// When any <li> is clicked, store its product-id
jQuery(document).on('click', '.b2bking_bulkorder_form_container_cream_filters .b2bking_bulkorder_filters_list li', function() {
    selectedProductID = jQuery(this).attr('product-id');
    jQuery('.b2bking_bulkorder_filters_list li').css('text-decoration','none').css('color','#333'); 
    jQuery('.parts-category-menu').css('color','#333');  
    jQuery(this).css('color','#e10915'); 
    //console.log('Selected Product ID:', selectedProductID);
});

// Then intercept AJAX and inject it
jQuery(document).ajaxSend(function(event, xhr, settings) {
    if (settings.data && settings.data.includes('action=b2bking_ajax_search')) {  

        if (selectedProductID) {
            // Add your extra data
            let extraData = '&selectedProductID=' + selectedProductID;
            settings.data += extraData;
            //console.log('Modified AJAX data1:', settings.data);
            jQuery('.b2bking_bulkorder_form_container_top_indigo.b2bking_bulkorder_form_container_top_cream').fadeIn(100);
        }else{
            let extraData = '&selectedProductID=' + 0;
            settings.data += extraData;
            //console.log('Modified AJAX data2:', settings.data);
        }
    }
});





// ----------- Cart/Checkout flow enhancement code START here   -----------------------

// Cart Item edit button click event
jQuery(function($){

    function cpaBindEditButton(){

        $(document).off('click', '.cpa-edit-btn');

        $(document).on('click', '.cpa-edit-btn', function(e){
            e.preventDefault();

            var key = $(this).data('key');

            $('.cpa-edit-form').hide();

            $('#cpa-edit-'+key).toggle();
        });

    }

    // run first time
    cpaBindEditButton();

    // run again after WooCommerce refresh
    $(document.body).on('updated_cart_totals updated_wc_div', function(){
        cpaBindEditButton();
    });

});
jQuery(document).ready(function(){

    var openKey = sessionStorage.getItem('cpa_edit_open');

    if(openKey){
        jQuery('.cpa-edit-form[data-cart-key="'+openKey+'"]').show();
    }
});

// Update the cart item attributes values on cart page
/*jQuery(document).on('click', '.cpa-save-btn', function(e){

    e.preventDefault();

    var key = jQuery(this).data('cart-key');
    var form = jQuery('#cpa-edit-' + key);

    var data = {};

    form.find('input, select').each(function(){

        var name = jQuery(this).attr('name');
        var value = jQuery(this).val();

        data[name] = value;

    });

    jQuery.post(
        wc_cart_params.ajax_url,
        {
            action: 'cpa_update_cart_item',
            cart_item_key: key,
            fields: data
        },
        function(){

            /* trigger WooCommerce cart refresh */
            /*jQuery('button[name="update_cart"]').prop('disabled', false);
            jQuery('button[name="update_cart"]').trigger('click');

        }
    );

});*/
jQuery(document).on('click', '.cpa-save-btn', function(e){

    e.preventDefault();

    var key  = jQuery(this).data('cart-key');
    var form = jQuery('#cpa-edit-' + key);

    var data = {};
    var hasError = false;

    /* LOOP ALL INPUTS */
    form.find('input, select').each(function(){

        var field = jQuery(this);
        var name  = field.attr('name');
        var value = field.val();

        field.removeClass('cpa-error');
        field.next('.cpa-error-text').remove();

        /* VALIDATE REQUIRED FIELD */
        if(field.data('required') == 1){

            if(!value || value.trim() === ''){

                hasError = true;

                field.addClass('cpa-error');

                field.after('<div class="cpa-error-text">This field is required</div>');
            }
        }

        data[name] = value;

    });

    /* STOP IF ERROR */
    if(hasError){
        alert('Please fill all required fields.');
        return false;
    }

    /* AJAX UPDATE CART */
    jQuery.ajax({

        url: wc_cart_params.ajax_url,

        type: 'POST',

        data: {
            action: 'cpa_update_cart_item',
            cart_item_key: key,
            fields: data
        },

        beforeSend: function(){

            form.find('.cpa-save-btn').text('Updating...');

        },

        success: function(response){

            if(response.success){

                location.reload();

            }else{

                alert('Update failed');

            }

        },

        complete: function(){

            form.find('.cpa-save-btn').text('Update');

        }

    });

});

// ----------- Cart/Checkout flow enhancement code END here   -----------------------