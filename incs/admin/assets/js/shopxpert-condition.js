// Fields Conditions System
;( function ( $ ) {
	"use strict";

    $.fn.shopxpert_conditions = function() {
        return this.each( function() {
    
            var $this   = $(this),
                $fields = $this.children('[data-controller]');
    
            if( $fields.length ) {
    
                var condition_ruleset = $.shopxpert_deps.createRuleset(),
                    all_conditions = [];
    
                $fields.each( function() {
    
                    var $field      = $(this),
                        controllers = $field.data('controller').split('|'),
                        conditions  = $field.data('condition').split('|'),
                        values      = $field.data('value').toString().split('|'),
                        ruleset     = condition_ruleset;
    
                    $.each( controllers, function( index, depend_id ) {
    
                        var value     = values[index] || '',
                            condition = conditions[index] || conditions[0];
                        
                        ruleset = ruleset.createRule('[data-depend-id="'+ depend_id +'"]', condition, value );
    
                        ruleset.include( $field );
    
                        all_conditions.push( depend_id );
    
                    });
    
                });
    
                if ( all_conditions.length ) {
                    $.shopxpert_deps.enable($this, condition_ruleset, all_conditions);
                }
    
            }
    
        });
    };
})(jQuery);
