jQuery(document).ready(function(){

    jQuery('a.popover-custom').popover({ 
        html : true,
        trigger: 'hover',
        placement: 'top',
        container: 'body'
      
    });

    jQuery(document).on('click','.jump-to-add-custom-foods',function(){
        jQuery("#food-diary-tabs>ul>li:last>a").click();
        jQuery(this).closest('.mm-accordion').find('h3:last').click();
        jQuery('html, body').animate({
            scrollTop: jQuery('#add_own_ingredient').offset().top-100
        }, 600);

    });
    
    jQuery('#food_diary_name, .search-ingredient-input').val('');
    jQuery('#meals-qty').val(1);
    
    var food_validator = jQuery("#food-diary-form").validate({
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            jQuery('html, body').animate({
                scrollTop: jQuery(validator.errorList[0].element).offset().top-100
            }, 600);

        }
    });

    jQuery(document).on('click', '.search-ingredient-btn', function(){
																	
        var query = jQuery(this).closest('tr.meal-add-ingredient').find('.search-ingredient-input').val();
        var _this = jQuery(this);
        _this.closest('tr.meal-add-ingredient').find('.search-ingredient-results').html('');
        if(query.length > 0){
            _this.addClass('loading');

            //if this is search in custon ingredients database
            if(jQuery(_this).closest('tr').find('.search_in_custom').is(":checked")){
                jQuery.ajax({
                    type: "POST",
                    url: mirrorMuscles.ajaxPath,
                    data: {action:'search_custom_ingredient', query:query},
                    success: function(data) {
                            
                            var callback = jQuery.parseJSON(data);

                            if(!callback.error){
                                var foods = '<select size="5" class="search-ingredient-selectbox">';
                                jQuery.each(callback,function(i,e){
                                    foods += '<option value="'+e.food_id+'">'+e.food_name+'</option>';
                                });

                                foods += '</select><p><button type="button" class="choose-custom-ingredient-btn">Choose</button></p>';

                                _this.closest('tr.meal-add-ingredient').find('.search-ingredient-results').html(foods);

                            }
                            _this.removeClass('loading');
                        }
                });
            }else{//if this is fatsecret search

                jQuery.ajax({
                    type: "POST",
                    url: mirrorMuscles.fatsecretPath,
                    data: {action:'search_ingredient', query:query},
                    success: function(data) {
                            
                            var callback = jQuery.parseJSON(data);

                            if(!callback.error){
                                var foods = '<select size="5" class="search-ingredient-selectbox">';
                                jQuery.each(callback,function(i,e){
                                    var brand = (e.brand_name) ? ' ('+e.brand_name+')' : '';
                                    foods += '<option value="'+e.food_id+'">'+e.food_name+''+brand+'</option>';
                                });

                                foods += '</select><p><button type="button" class="choose-ingredient-btn">Choose</button></p>';

                                _this.closest('tr.meal-add-ingredient').find('.search-ingredient-results').html(foods);

                            }
                            _this.removeClass('loading');
                        }
                });
            }
        
            
        }
    });


    jQuery(document).on('click', '.choose-ingredient-btn,.choose-custom-ingredient-btn', function(){

        var _this = jQuery(this);
        var food_id = jQuery(this).closest('tr.meal-add-ingredient').find('.search-ingredient-selectbox option:selected').val();
        var url = (jQuery(this).hasClass('choose-ingredient-btn')) ? mirrorMuscles.fatsecretPath : mirrorMuscles.ajaxPath;
        var action = (jQuery(this).hasClass('choose-ingredient-btn')) ? 'get_food' : 'get_custom_food';
        _this.closest('td').find('.servings-container').remove();
        if(food_id){
            _this.addClass('loading');
            jQuery.ajax({
                type: "POST",
                dataType: "JSON",
                url: url,
                data: {action: action, food_id:food_id},
                success: function(callback) {

                        if(!callback.error){
                            var servings = '<div class="form-inline servings-container">\
                                <input type="number" min="0.5" step="0.5" class="search-servings-qty" value="0.5">\
                                servings of\
                                <select class="search-servings-selectbox">';
<!--<input type="number" min="1" step="1" class="search-servings-qty" value="1">-->
                                jQuery.each(callback,function(i,e){
                                    servings += '<option data-serving="'+i+'" value="'+e.serving_description+'">'+e.serving_description+'</option>';
                                });

                                servings += '</select><i class="fa fa-lg fa-eye" rel="popover" data-dismiss="popover" data-content="" data-html="true"></i>\
                                        <button  type="button" class="btn add-serving-btn">Add</button></div>';

                            _this.closest('tr.meal-add-ingredient').find('.search-ingredient-results').append(servings);

                        }

                        

                        jQuery('[rel=popover]').on('click', function(){
                            var data_meal = jQuery(this).closest('table').data('meal');
                            var food_name = jQuery(this).closest('tr.meal-add-ingredient').find('.search-ingredient-selectbox option:selected').text();
                            var data_serving = jQuery(this).closest('tr.meal-add-ingredient').find('.search-servings-selectbox option:selected').data('serving');
                            var qty = parseInt(jQuery(this).closest('tr.meal-add-ingredient').find('.search-servings-qty').val());

                            var _blanks = '&nbsp;&nbsp;&nbsp;';
                            var _dots = '&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&middot;&nbsp;';
                            
                            var fat = (!callback[data_serving].fat) ? '-' : callback[data_serving].fat+'g';
                            var saturated_fat = (!callback[data_serving].saturated_fat) ? '-' : callback[data_serving].saturated_fat+'g';
                            var polyunsaturated_fat = (!callback[data_serving].polyunsaturated_fat) ? '-' : callback[data_serving].polyunsaturated_fat+'g';
                            var monounsaturated_fat = (!callback[data_serving].monounsaturated_fat) ? '-' : callback[data_serving].monounsaturated_fat+'g';
                            var trans_fat = (!callback[data_serving].trans_fat) ? '-' : callback[data_serving].trans_fat+'g';

                            var cholesterol = (!callback[data_serving].cholesterol) ? '-' : callback[data_serving].cholesterol+'mg';
                            var sodium = (!callback[data_serving].sodium) ? '-' : callback[data_serving].sodium+'mg';
                            var potassium = (!callback[data_serving].potassium) ? '-' : callback[data_serving].potassium+'mg';
                            
                            var carbohydrate = (!callback[data_serving].carbohydrate) ? '-' : callback[data_serving].carbohydrate+'g';
                            var fiber = (!callback[data_serving].fiber) ? '-' : callback[data_serving].fiber+'g';
                            var sugar = (!callback[data_serving].sugar) ? '-' : callback[data_serving].sugar+'g';
                            var protein = (!callback[data_serving].protein) ? '-' : callback[data_serving].protein+'g';

                            var vitamin_a = (!callback[data_serving].vitamin_a) ? '-' : callback[data_serving].vitamin_a+'%';
                            var vitamin_c = (!callback[data_serving].vitamin_c) ? '-' : callback[data_serving].vitamin_c+'%';
                            var calcium = (!callback[data_serving].calcium) ? '-' : callback[data_serving].calcium+'%';
                            var iron = (!callback[data_serving].iron) ? '-' : callback[data_serving].iron+'%';
                            
                            var infopanel = '<p class="text-center"><b><u>Amount per serving:</u></b></p>'+
                                            '<hr>'+
                                            '<p class="text-center"><small><b>Serving size:</b> '+callback[data_serving].serving_description+'</small></p>'+
                                            '<p class="text-center"><small><b>Calories:</b> '+callback[data_serving].calories+' kcal</small></p>'+
                                            '<hr>'+
                                            '<p class="text-left"><small><b>Total Fat </b>'+_dots+fat+'</small></p>'+
                                            '<p class="text-left">'+_blanks+'<small>Saturated Fat '+_dots+saturated_fat+'</small></p>'+
                                            '<p class="text-left">'+_blanks+'<small>Polyunsaturated Fat '+_dots+polyunsaturated_fat+'</small></p>'+
                                            '<p class="text-left">'+_blanks+'<small>Monounsaturated Fat '+_dots+monounsaturated_fat+'</small></p>'+
                                            '<p class="text-left">'+_blanks+'<small>Trans Fat '+_dots+trans_fat+'</small></p>'+
                                            
                                            '<p class="text-left"><small><b>Cholesterol </b>'+_dots+cholesterol+'</small></p>'+
                                            '<p class="text-left"><small><b>Sodium </b>'+_dots+sodium+'</small></p>'+
                                            '<p class="text-left"><small><b>Potasium </b>'+_dots+potassium+'</small></p>'+
                                            
                                            '<p class="text-left"><small><b>Total Carbohydrate </b>'+_dots+carbohydrate+'</small></p>'+
                                            '<p class="text-left">'+_blanks+'<small>Dietary Fiber '+_dots+fiber+'</small></p>'+
                                            '<p class="text-left">'+_blanks+'<small>Sugars '+_dots+sugar+'</small></p>'+
                                            '<p class="text-left"><small><b>Protein </b>'+_dots+protein+'</small></p>'+
                                            '<hr>'+
                                            '<p class="text-center"><small><b>Vitamin A: </b>'+vitamin_a+'</small>'+_blanks+'<small><b>Vitamin C: </b>'+vitamin_c+'</small></p>'+
                                            '<p class="text-center"><small><b>Calcium: </b>'+calcium+'</small>'+_blanks+'<small><b>Iron: </b>'+iron+'</small></p>';
                            

                            jQuery(this).closest('tr.meal-add-ingredient').find('i[rel="popover"]')
                            .attr('data-content',infopanel)
                            .attr('data-original-title','<a class="close">&times;</a><h4 class="text-center">'+food_name+'</h4>');

                        });

                        jQuery('[rel=popover]').popover({ 
                            html : true,
                            trigger: 'click',
                            placement: 'left',
                            container: '.servings-container'
                          
                        }).on('shown.bs.popover', function(e){
                            var popover = jQuery(this);
                            jQuery(this).parent().find('div.popover .close').on('click', function(e){
                                popover.popover('hide');
                                popover.trigger("click");
                            });

                            jQuery('.add-serving-btn').on('click',function(){
                                //if(jQuery("#food-diary-form").valid()){
                                    popover.popover('hide');
                                    if(jQuery('.popover').hasClass('in')){
                                        popover.trigger("click");
                                    }
                                //}
                            });
                        });

                        jQuery('.add-serving-btn').on('click', function(){
                            //if(jQuery("#food-diary-form").valid()){
                                var data_meal = jQuery(this).closest('table').data('meal');
                                var data_serving = jQuery(this).closest('tr.meal-add-ingredient').find('.search-servings-selectbox option:selected').data('serving');
                                var food_id = jQuery(this).closest('tr.meal-add-ingredient').find('.search-ingredient-selectbox option:selected').val();
                                var food_name = jQuery(this).closest('tr.meal-add-ingredient').find('.search-ingredient-selectbox option:selected').text();
                                var qty = parseFloat(jQuery(this).closest('tr.meal-add-ingredient').find('.search-servings-qty').val());
                                
                                var serving_string = (qty*callback[data_serving].number_of_units)+' '+callback[data_serving].measurement_description;
                                var calories = parseFloat((qty*callback[data_serving].calories).toFixed(2));
                                var protein = parseFloat((qty*callback[data_serving].protein).toFixed(2));
                                var fat = parseFloat((qty*callback[data_serving].fat).toFixed(2));
                                var carbs = parseFloat((qty*callback[data_serving].carbohydrate).toFixed(2));
                                
                                var row = '<tr class="ingredient-row">'+
                                                '<td>'+food_name+', '+serving_string+'</td>'+
                                                '<td>'+calories+'</td>'+
                                                '<td>'+protein+'</td>'+
                                                '<td>'+fat+'</td>'+
                                                '<td>'+carbs+'</td>'+
                                                '<td><i class="fa fa-lg fa-trash remove-ingredient"></i></td>'+
                                            '</tr>';

                                jQuery('#meal-table-'+data_meal+' tr.meal-add-ingredient').before(row);

                                var total_calories = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .calories').text()) + calories;
                                jQuery('#meal-table-'+data_meal+' tr.meal-total .calories').text( parseFloat(total_calories.toFixed(2)) );
                                var total_protein = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .protein').text()) + protein;
                                jQuery('#meal-table-'+data_meal+' tr.meal-total .protein').text( parseFloat(total_protein.toFixed(2)) );
                                var total_fat = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .fat').text()) + fat;
                                jQuery('#meal-table-'+data_meal+' tr.meal-total .fat').text( parseFloat(total_fat.toFixed(2)) );
                                var total_carbs = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .carbs').text()) + carbs;
                                jQuery('#meal-table-'+data_meal+' tr.meal-total .carbs').text( parseFloat(total_carbs.toFixed(2)) );
                            //}
                        });
                        
                        _this.removeClass('loading');
                        
                    }
            });//end ajax choose ingredient
        }//end if food_id
    });
    
    //remove ingredients row
    jQuery(document).on('click','.remove-ingredient',function(){
        var data_meal = jQuery(this).closest('table').data('meal');

        var calories = parseFloat(jQuery(this).closest('tr').find('td:eq(1)').text());
        var protein = parseFloat(jQuery(this).closest('tr').find('td:eq(2)').text());
        var fat = parseFloat(jQuery(this).closest('tr').find('td:eq(3)').text());
        var carbs = parseFloat(jQuery(this).closest('tr').find('td:eq(4)').text());



        var total_calories = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .calories').text()) - calories;
        jQuery('#meal-table-'+data_meal+' tr.meal-total .calories').text( parseFloat(total_calories.toFixed(2)) );
        var total_protein = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .protein').text()) - protein;
        jQuery('#meal-table-'+data_meal+' tr.meal-total .protein').text( parseFloat(total_protein.toFixed(2)) );
        var total_fat = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .fat').text()) - fat;
        jQuery('#meal-table-'+data_meal+' tr.meal-total .fat').text( parseFloat(total_fat.toFixed(2)) );
        var total_carbs = parseFloat(jQuery('#meal-table-'+data_meal+' tr.meal-total .carbs').text()) - carbs;
        jQuery('#meal-table-'+data_meal+' tr.meal-total .carbs').text( parseFloat(total_carbs.toFixed(2)) );
                        
        jQuery(this).closest('tr').fadeOut(600, function() {jQuery(this).closest('tr').remove();});
    });

    //function for adding meal table
    var print_meal_table = function(meal){
        var table = '<table id="meal-table-'+meal+'" data-meal="'+meal+'" class="meal-table">'+
                        '<thead>'+
                            '<tr class="meal-title">'+
                                '<th colspan="6" class="text-center">Meal '+meal+'</th>'+
                            '</tr>'+
                            '<tr class="meal-header">'+
                                '<th style="width: 35%;">Ingredients</th>'+
                                '<th style="width: 15%;" class="calories">Calories</th>'+
                                '<th style="width: 15%;" class="protein">Protein</th>'+
                                '<th style="width: 15%;" class="fat">Fat</th>'+
                                '<th style="width: 15%;" class="carbs">Carbs</th>'+
                                '<th><small>Action</small></th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+
                            '<tr class="meal-add-ingredient">'+
                                '<td class="text-center">'+
                                    '<p>Add ingredient:</p>'+
                                    '<p><input type="text" class="search-ingredient-input" placeholder="Search ingredient..."></p>'+
                                    '<small><label style="font-weight: 400"><input type="checkbox" class="search_in_custom styled"><strong></strong> Search in Mirror Muscles <a class="popover-custom" data-toggle="popover" data-content="Search for ingredients that was added to the Custom Ingredients Database by Mirror Muscles members." style="cursor:pointer" data-original-title="" title="">Custom Ingredients</a></label></small>'+
                                    '<p><button type="button" class="btn search-ingredient-btn">Search</button></p>'+
                                    '<p><small>or <a class="jump-to-add-custom-foods">Add custom foods</a></small></p>'+
                                '</td>'+
                                '<td colspan="5" class="search-ingredient-results"></td>'+
                            '</tr>'+
                        '</tbody>'+
                        '<tfoot>'+
                            '<tr class="meal-total">'+
                                '<td class="text-right">Total:</td>'+
                                '<td class="calories">0</td>'+
                                '<td class="protein">0</td>'+
                                '<td class="fat">0</td>'+
                                '<td class="carbs">0</td>'+
                                '<td>&nbsp;</td>'+
                            '</tr>'+
                        '</tfoot>'+
                    '</table>';
        return table;
    }

    
    jQuery(document).on('change','#meals-qty',function(){
        var qty = jQuery(this).val();
        var tables_qty = jQuery('.meal-table').length;
        
        if( qty>1 && food_validator.element( "#meals-qty" ) ){
            if( tables_qty < qty ){
                for(i=tables_qty; i<qty; i++){
                    jQuery('#meals-tbls-container').append( print_meal_table(i+1) );
                }
            }else if( tables_qty > qty ){

                for(i=tables_qty; i>=qty; i--){
                    jQuery('#meal-table-'+(i+1)).remove();
                }
            }
        }

        if( qty==1 && food_validator.element( "#meals-qty" ) && tables_qty > qty ){
            jQuery('#meal-table-2').remove();
        }

         jQuery('a.popover-custom').popover({ 
            html : true,
            trigger: 'hover',
            placement: 'top',
            container: 'body'
        });
    
    });


    jQuery(document).on('click','#save_food_diary', function(event){
            
        event.preventDefault();
        _this = jQuery(this);

        var diary_name = jQuery('#food_diary_name').val();

        if( jQuery('#food-diary-form').valid() && jQuery('.meal-table tbody tr:not(.meal-add-ingredient)').length>0 ){

            _this.addClass('loading');

            _this.attr('disabled','disabled');

            var food_diary = {};

            jQuery('.meal-table').each(function(i,e){
                ingredients = [];
                jQuery(this).find('tbody tr:not(.meal-add-ingredient)').each(function(ind,el){
                    var name = jQuery(this).find('td:eq(0)').text().trim();
                    var calories = jQuery(this).find('td:eq(1)').text().trim();
                    var protein = jQuery(this).find('td:eq(2)').text().trim();
                    var fat = jQuery(this).find('td:eq(3)').text().trim();
                    var carbs = jQuery(this).find('td:eq(4)').text().trim();
                    ingredients.push({name : name, calories : calories, protein : protein, fat : fat, carbs : carbs });
                });
                food_diary[i] = { diary_name: diary_name, ingredients:ingredients };
            });
            
            jQuery.ajax({
                type: "POST",
                url: mirrorMuscles.ajaxPath,
                data: {save_food_diary: 1, meals:food_diary},
                success: function(data) {
                        _this.removeClass('loading');
                        window.location.reload();
                    }
            });
        }            
    });


    jQuery(document).on('click', '.print-food-plan', function(event){
        event.preventDefault();
        jQuery(this).closest('.food-plan-container').printElement({pageTitle:'My Nutrition Plan'});
    });

    jQuery(document).on('click','.delete-food-plan,.delete-supplement-plan',function(event){
        event.preventDefault();
        var _this = jQuery(this);
        var type = (_this.hasClass('delete-food-plan')) ? 'food' : 'supplement';
        var uniqid = jQuery(this).closest('.'+type+'-plan-container').data('uniqid');
        var plan_selector = jQuery(this).closest('.'+type+'-plan-container');
        _this.addClass('loading');
        jQuery.ajax({
            type: "POST",
            url: mirrorMuscles.ajaxPath,
            data: 'delete_'+type+'_plan=1&uniqid='+uniqid,
            success: function(data){
                    var callback = jQuery.parseJSON(data);
                    if(!callback.error){
                        _this.removeClass('loading');
                        plan_selector.fadeOut(600, function() {jQuery(this).remove();}); 
                    }
                    else{
                       window.location.reload();
                    }
            }
        });
    });

    jQuery(document).on('click','.share-food-plan,.share-supplement-plan,.unshare-food-plan,.unshare-supplement-plan',function(event){
        event.preventDefault();
        var _this = jQuery(this);
        var type = (_this.hasClass('share-food-plan') || _this.hasClass('unshare-food-plan')) ? 'food' : 'supplement';
        var action = (_this.hasClass('share-'+type+'-plan')) ? 'share' : 'unshare';
        var uniqid = jQuery(this).closest('.'+type+'-plan-container').data('uniqid');
        _this.addClass('loading');
        jQuery.ajax({
            type: "POST",
            url: mirrorMuscles.ajaxPath,
            data: action+'_'+type+'_plan=1&uniqid='+uniqid,
            success: function(data) {
                var callback = jQuery.parseJSON(data);

                if(!callback.error){
                    _this.removeClass('loading');
                    if(action == 'share')
                        _this.removeClass('share-'+type+'-plan').addClass('unshare-'+type+'-plan').html('<i class="fa fa-share-alt"> Unshare for Trainers/GYMs</i>');   
                    else
                        _this.removeClass('unshare-'+type+'-plan').addClass('share-'+type+'-plan').html('<i class="fa fa-share-alt"> Share for Trainers/GYMs</i>');
                }
                else{
                   window.location.reload();
                }
            }
        }); 
    });



    jQuery(document).on('click', '.to-wall-food-plan', function(event){
        event.preventDefault();
        
        var _this = jQuery(this);
        var food_plan_selector = jQuery(this).closest('.food-plan-container').find('.food-plan-share-container');
        var uniqid = jQuery(this).closest('.food-plan-container').data('uniqid');
        
        scrollPos = document.body.scrollTop;
        html2canvas(food_plan_selector, { 
            background: '#E7FCFF',
            onrendered: function(canvas) {
                jQuery(window).scrollTo(0,scrollPos);
                _this.addClass('loading');
                _this.attr('disabled','disabled');
                
               var imgData = canvas.toDataURL('image/jpeg');   

                jQuery.ajax({ 
                    type: "POST", 
                    url: mirrorMuscles.ajaxPath,
                    dataType: 'text',
                    data: {to_wall_food_plan : 1, uniqid : uniqid, canvas : imgData,},
                    success: function(data){
                        window.location.reload();
                    }
                });
            }

        }); //End html2canvas
    });


    jQuery("#add_own_ingredient").validate({
        errorPlacement: function(error, element){
                var name = element.attr("name");
                jQuery('#error-' + name).append(error);
            },
        invalidHandler: function(form, validator) {

            if (!validator.numberOfInvalids())
                return;

            jQuery('html, body').animate({
                scrollTop: jQuery(validator.errorList[0].element).offset().top-100
            }, 600);

        }
    });

    jQuery(document).on('click','#save_new_ingredient',function(){
        var _this = jQuery(this);
       if(jQuery("#add_own_ingredient").valid()){
            _this.addClass('loading').attr('disabled','disabled');
            var data = jQuery("#add_own_ingredient").serialize();
            jQuery.ajax({
                type: "POST",
                dataType: 'JSON',
                url: mirrorMuscles.ajaxPath,
                data: {action: 'save_new_ingredient', data:data},
                success: function(data){
                        
                        if(data.success){
                            _this.removeClass('loading').attr('disabled',false);
                            _this.before('<div id="message" class="updated text-center new-ingredient-saved"><p>New ingredient successfully saved.</p></div>');
                            setTimeout(function(){
                                jQuery('.new-ingredient-saved').remove();
                            },2500);
                        }
                        else
                           window.location.reload();
                }
            });
            //jQuery('#add_own_ingredient').submit();
       }
    });
    

    //print supplements plans
    jQuery(document).on('click', '.print-supplement-plan', function(){
        jQuery('.print_container').empty();
        var table = jQuery(this).closest('.supplement-plan-container').find('.supplements-table-saved').clone().appendTo('.print_container');
        var plan_name = jQuery(this).closest('.supplement-plan-container').find('.diary-plan-title').text();

        jQuery('.print_container').append('<h3 class="diary-plan-title">'+plan_name+'</h3>');
        jQuery('.print_container').append('<p class="text-left">Starting Date: ___ / ___ / ___<br>Ending Date: ___ / ___ / ___</p>');

        var weekday=new Array(7);
        weekday[7]="Sunday";
        weekday[1]="Monday";
        weekday[2]="Tuesday";
        weekday[3]="Wednesday";
        weekday[4]="Thursday";
        weekday[5]="Friday";
        weekday[6]="Saturday";
        
        jQuery('.print_container table').attr('id','tmp_tbl');

        for (i=1; i<=7; i++){
            day = weekday[i];

            var max_doses = -Infinity;
            jQuery('.print_container #tmp_tbl tbody tr').each(function(i,e){
                dose_per_day = parseInt(jQuery('td:eq(1)',this).text());
                max_doses = Math.max(max_doses, dose_per_day);
            });

            var day_tbl = '<table><tbody>';

            day_tbl += '<tr><td class="dayname heads">'+day+'</td>';
                for(dose=1; dose<=max_doses; dose++){
                    day_tbl += '<td class="heads">Dose '+dose+'</td>';
                }
            day_tbl += '</tr>';

            jQuery('.print_container #tmp_tbl tbody tr').each(function(i,e){
                supl_name = jQuery('td:eq(0)',this).text();
                dose_per_day = parseInt(jQuery('td:eq(1)',this).text());
                day_tbl += '<tr><td>'+supl_name+'</td>';

                    for(dose=1; dose<=max_doses; dose++){
                        if(dose>dose_per_day)
                            day_tbl += '<td class="dose_no">&nbsp;</td>';
                        else
                            day_tbl += '<td class="dose_yes">&nbsp;</td>';
                    }
                day_tbl += '</tr>';
            });
            day_tbl += '</tbody></table>';
            jQuery('.print_container').append(day_tbl);
            
        }
        jQuery('.print_container #tmp_tbl').remove();
        jQuery('.print_container').printElement({pageTitle:'My Nutrition Plans'});


    });

   

    
    
    
    jQuery("#supplement-diary-form").validate({
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            jQuery('html, body').animate({
                scrollTop: jQuery(validator.errorList[0].element).offset().top-100
            }, 600);
        }
    });

    jQuery(document).on('change','#supplements_qty',function(){
        var supplements_qty = jQuery('#supplements_qty option:selected').val();
        jQuery('#supplements_tbls').empty();
        for(i=1; i<=supplements_qty; i++){

            jQuery('#supplements_tbls').append('<div id="supplements_tbls_container_'+i+'" class="supplements-tbls-container">\
                    <table id="supplements_tbl_'+i+'" class="supplements_tbl">\
                        <tbody>\
                            <tr>\
                                <td class="supplement_cell" width="30%">\
                                    <div class="form-group">\
                                        <label for="supplement_name_'+i+'" class="control-label text-right">Name:</label>\
                                        <input type="text" class="form-control" name="supplement_name_'+i+'" id="supplement_name_'+i+'" required>\
                                    </div>\
                                </td>\
                                <td class="supplement_cell" width="20%">\
                                    <div class="form-group">\
                                        <label for="supplement_units_'+i+'" class="control-label text-right">Units:</label>\
                                        <select id="supplement_units_'+i+'" class="form-control" name="supplement_units_'+i+'" required>\
                                            <option value="Capsules">Capsules</option>\
                                            <option value="Gramms">Gramms</option>\
                                        </select>\
                                    </div>\
                                </td>\
                                <td class="supplement_cell" width="20%">\
                                    <div class="form-group">\
                                        <label for="supplement_amount_'+i+'" class="control-label text-right">Amount:</label>\
                                        <input type="number" min="1" class="form-control" name="supplement_amount_'+i+'" id="supplement_amount_'+i+'" required>\
                                    </div>\
                                </td>\
                                <td class="supplement_cell" width="20%">\
                                    <div class="form-group">\
                                        <label for="supplement_per_day_'+i+'" class="control-label text-right">Times Taken Per Day:</label>\
                                        <select id="supplement_per_day_'+i+'" class="form-control" name="supplement_per_day_'+i+'" required>\
                                            <option value="1">1</option>\
                                            <option value="2">2</option>\
                                            <option value="3">3</option>\
                                            <option value="4">4</option>\
                                            <option value="5">5</option>\
                                            <option value="6">6</option>\
                                            <option value="7">7</option>\
                                            <option value="8">8</option>\
                                            <option value="9">9</option>\
                                            <option value="10">10</option>\
                                        </select>\
                                    </div>\
                                </td>\
                                <td class="remove_supplement" width="10%">\
                                    <i id="remove_supplement_'+i+'" class="remove-supplement fa fa-2x fa-trash"></i>\
                                </td>\
                            </tr>\
                        </tbody>\
                    </table>\
                </div>');
        }//endfor
    });


    jQuery(document).on('click','.remove-supplement',function(event){
        event.preventDefault();
        var _this = jQuery(this);
        _this.closest('table').fadeOut(600,function(){
            _this.closest('table').remove();
        });
    });
    
    jQuery(document).on('click','#save_supplement_diary',function(event){
        
        event.preventDefault();
        
        _this = jQuery(this);
        if(jQuery('#supplement-diary-form').valid()){
            jQuery(this).addClass('loading').prop('disabled',true);
            var supplements = [];
            
            jQuery('.supplements-tbls-container').each(function(i,e){
                var supplement_id = jQuery(this).attr('id');
                var params = jQuery('#'+supplement_id+' :input').serializeArray();
                supplements.push(params);
            });

            jQuery.ajax({
                type: "POST",
                url: mirrorMuscles.ajaxPath,
                data: {save_new_supplements_diary:1,diary_name: jQuery('#supplements_diary_name').val(),supplements:supplements},
                success: function(data) {
                    _this.removeClass('loading');
                    window.location.reload();
                        
                }
            });
        }
        
    });

});