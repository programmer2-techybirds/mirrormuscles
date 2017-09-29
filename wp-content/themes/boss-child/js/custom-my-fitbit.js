function printFitbitValues (type, first, second, firstLabel, secondLabel) {

    //day values
    day_first = first.pop();
    day_second = second.pop();
    day_first_ = first.shift();
    day_second_ = second.shift();

    var content = '';
    content = '<div class="fitbit-day-values">';
        content += '<h3 class="result-title">Fitbit '+type+' on '+jQuery.datepicker.formatDate('MM d, yy', new Date(day_first.dateTime))+'</h3>';
        content += '<p>'+firstLabel+': '+day_first.value+'</p>';
        content += '<p>'+secondLabel+': '+day_second.value+'</p>';
    content += '</div>';
    content += '<h3 class="result-title">last 30 days</h3>';

    jQuery('#fitbit-'+type+'-results').html(content);



    var first_ = first.map(function (obj) {
        return [Date.parse(obj.dateTime),parseInt(obj.value)];
    });

    var second_ = second.map(function (obj) {
        return [Date.parse(obj.dateTime),parseInt(obj.value)];
    });


	jQuery(function () {        
        var plot = jQuery.plot(jQuery('#fitbit-'+type+'-plot'),
            [
                {
                  data: first_,
                  label: firstLabel,
                  points: { show: true },
                  lines: { show: true},
                  color: '#FD6703',
                  hoverable: true
                },
                {
                  data: second_,
                  label: secondLabel,
                  points: { show: true },
                  lines: { show: true},
                  hoverable: true,
                  yaxis: 2,
                  color: '#4DCADE'
                }
            ],
            {      
                canvas: true,      
                grid: {
                    hoverable: true,
                    backgroundColor: '#fff',
                    minBorderMargin: 20,
                    borderWidth: 2,
                    margin: {
                        top: 5,
                        left: 5,
                        bottom: 5,
                        right: 5,
                    },
                    borderColor: {
                        top: '#fff',
                        left: '#fff',
                        bottom: '#fff',
                        right: '#fff'
                    }
                },
                
                legend:{
                    show: true,
                    container: '#fitbit-'+type+'-plot-legend',
                    noColumns: 2,
                    labelBoxBorderColor: 'transparent',
                    labelFormatter: function(label, series) {
                    return '<span style="color:#30455C;">' + label + '</span>';
                    }
                },
                xaxis: {
                    show: true,
                    color: 'rgba(0,0,0, 0.1)',
                    mode: "time",
                    timeformat: "%m-%d-%y",
                    font: {
                            size: 11,
                            lineHeight: 13,
                            style: "normal",
                            weight: "400",
                            family: "Ubuntu",
                            variant: "small-caps",
                            color: "#30455C"
                        }
                    
                },
                yaxes: [
                    {
                    color: 'rgba(0,0,0, 0.1)',
                    font: {
                            size: 11,
                            lineHeight: 13,
                            style: "normal",
                            weight: "400",
                            family: "Ubuntu",
                            variant: "small-caps",
                            color: "#30455C"
                        },
                    },

                    {
                        position: "right",
                        color: 'rgba(0,0,0, 0.1)',
                        font: {
                            size: 11,
                            lineHeight: 13,
                            style: "normal",
                            weight: "400",
                            family: "Ubuntu",
                            variant: "small-caps",
                            color: "#30455C"
                        },
                    }
                ]

            }
        );

        var canvas = plot.getCanvas();
        var img = canvas.toDataURL("image/png");
        jQuery('#fitbit-'+type+'-plot').append('<img class="fitbit-canvas-print" src="' + img + '" style="display:none;"/>');

    });

    
}


var getFitbitData = function(date,type){

        jQuery('.fitbit-loading[data-type="'+type+'"]').show();
        var limit = parseInt(jQuery('.rate-limit small span').text());
        
        jQuery.ajax({
            type: "POST",
            url: mirrorMuscles.fitbitPath,
            dataType: 'JSON',
            data: {action:'get-fitbit-'+type, date: date},
            success: function(callback) {
        
                if(callback.success){

                    jQuery('.fitbit-loading[data-type="'+type+'"]').hide();
                    jQuery('#fitbit-'+type+'-sharing-container').css('visibility', 'visible');
                    if(type == 'calories')
                        printFitbitValues('calories',callback.success.caloriesIn,callback.success.caloriesOut, 'Calories consumed', 'Calories burned');
                    if(type == 'steps')
                        printFitbitValues('steps',callback.success.steps,callback.success.distance, 'Steps', 'Distance');
                    if(type == 'sleep')
                        printFitbitValues('sleep',callback.success.sleep,callback.success.awakenings, 'Sleep', 'Awakenings');

                    jQuery('.rate-limit small span').text(limit-2);

                }else{
                    jQuery('.fitbit-loading[data-type="'+type+'"]').hide();
                    jQuery('#fitbit-'+type+'-sharing-container').css('visibility', 'hidden');
                    jQuery('.rate-limit small span').text('0');
                }
                    
            }
        });
    }



jQuery(document).ready(function(jQuery){

    jQuery('[rel=popover]').popover({ 
          html : true,
          trigger: "hover",
          placement: 'top',
          container: 'body'
      
      });

    


    jQuery(document).on('click','.share-fitbit-account:not(.disabled), .unshare-fitbit-account:not(:disabled)',function(event){
        event.preventDefault();
        var _this = jQuery(this);
        var action = (_this.hasClass('share-fitbit-account')) ? 'share' : 'unshare';
        _this.addClass('loading disabled');
        jQuery.ajax({
            type: "POST",
            dataType: 'JSON',
            url: mirrorMuscles.ajaxPath,
            data: {action: action+'-fitbit-account'},
            success: function(data) {
                if(data.success){
                    _this.removeClass('loading disabled');
                    if(action == 'share')
                        _this.removeClass('share-fitbit-account').addClass('unshare-fitbit-account').html('<i class="fa fa-share-alt"> Unshare for Trainers/GYMs</i>');   
                    else
                        _this.removeClass('unshare-fitbit-account').addClass('share-fitbit-account').html('<i class="fa fa-share-alt"> Share for Trainers/GYMs</i>');
                }
                else{
                   window.location.reload();
                }
            }
        }); 
    });


    jQuery(".fitbit-plot").bind("plothover", function (event, pos, item) {
      if (item) {
        var label = item.series.label;
        var date = new Date(item.datapoint[0]);
        var content_date = date.getFullYear()+'-'+('0'+(date.getMonth() + 1)).slice(-2)+'-'+('0'+date.getDate()).slice(-2);
        jQuery("#flot-chart-tooltip").remove();
        var content = 'Saved: <strong>'+content_date+'</strong>,<br> '+label+': <strong>'+item.datapoint[1]+'</strong>';
        jQuery('body').append('<div id="flot-chart-tooltip" style="top:'+(item.pageY + 5)+'px; left: '+(item.pageX + 5)+'px">' + content + '</div>');
      } else {
        jQuery("#flot-chart-tooltip").remove();
      }
    });


    jQuery(document).on('click', '.fitbit-share', function(event){
        event.preventDefault();
        var _this = jQuery(this);
        _this.closest(".fitbit-sharing-container").find('.fitbit-canvas-print').remove();
        scrollPos = document.body.scrollTop;
        
        html2canvas(_this.closest(".fitbit-sharing-container").find('.fitbit-print-container'), { 
            background:'#e7fcff',
            onrendered: function(canvas) {
                
                window.scrollTo(0,scrollPos);
                _this.addClass('loading');
                _this.attr('disabled','disabled');

                var imgData = canvas.toDataURL('image/jpeg');
                jQuery.ajax({ 
                    type: "POST", 
                    url: mirrorMuscles.ajaxPath,
                    dataType: 'text',
                    data: {action:'to-wall-fitbit', canvas : imgData},
                    success: function(data){
                        window.location.reload();
                    }
                }); 
            }

        }); //End html2canvas
    });


    jQuery(document).on('click', '.fitbit-print', function(event){
        event.preventDefault();
        jQuery(this).closest(".fitbit-sharing-container").find('.fitbit-print-container').printElement({pageTitle:'My Fitbit results'});
    });


});