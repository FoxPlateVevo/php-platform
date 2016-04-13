/*
 * Version 0.1
 */
(function($){
    /*
     * action : optional String [auto]
     */
    $.fn.chainSelects = function(action) {
        this.each(function(){
            var $context = $(this),
            $selects = $context.find("select[data-action][data-target]");
            
            $selects.on("change", function(){
                doSelectAction.bind(this)($context);
            });
            
            if(action === "auto"){
                var index = 0;
            
                (function exec(){
                    var select = $selects.get(index);

                    if(select){
                        doSelectAction.bind(select)($context, function(){
                            exec();
                        });

                        index++;
                    }
                })();
            }
        });
        
        function doSelectAction($context, callback){
            var $select = $(this),
            action      = $select.attr("data-action"),
            target      = $select.attr("data-target");

            var params = {};
            params[$select.attr("name")] = $select.val();

            $.get(action, params, function(options){
                var $targetSelect = $context.find("select[name=" + target + "]");

                $targetSelect
                .empty()
                .material_select("destroy");
                
                if(options instanceof Array){
                    options.forEach(function(option){
                        $targetSelect.append($("<option>").attr("value", option.value).text(option.text));
                    });
                }else{
                    $.each(options, function(key, option){
                        $targetSelect.append($("<option>").attr("value", option.value).text(option.text));
                    });
                }
                
                $targetSelect.material_select();

                typeof callback === "function" && callback();
            }, "json");
        }
        
        return this;
    };
    
    $.fn.send = function(callback, dataType) {
        this.each(function(){
            var form = this,
            $form = $(form);
            
            if($form.is("form") && $form.attr("action")){
                $form.submit(submitHandler);
            }
        });
        
        function submitHandler(e) {
            e.preventDefault();
            
            var form        = this,
            action          = $(form).attr("action"),
            formElements    = form.elements,
            params          = {};

            $.each(formElements, function(i, element){
                if(element.name){
                    var value, success = true;
                    
                    switch (element.type) {
                        case "button":
                        case "file":
                        case "hidden":
                        case "number":
                        case "password":
                        case "select-one":
                        case "submit":
                        case "text":
                        case "textarea":
                            value = element.value;
                            break;
                        case "checkbox":
                        case "radio":
                            if (element.checked) {
                                value = element.value;
                            } else {
                                success = false;
                            }
                            break;
                    }

                    if(success){
                        var elementName = element.name;
                        value = value.replace(/'/g, '"').replace(/&/g, " ");

                        switch (element.type){
                            case "checkbox":
                                if(elementName.match(/\[\]/)){
                                    elementName = elementName.replace(/\[\]/, "");

                                    if(!params[elementName]){
                                        params[elementName] = [];
                                    }

                                    params[elementName].push(value);
                                }else{
                                    params[elementName] = value;
                                }
                                break;
                            default:
                                params[elementName] = value;
                                break;
                        }
                    }
                }
            });

            var disabledElements = function(disabled){
                $(formElements).each(function(){
                    switch (this.type) {
                            case "button":
                            case "checkbox":
                            case "file":
                            case "select-one":
                            case "radio":
                            case "submit":
                            case "textarea":
                                $(this).attr("disabled", disabled);
                                break;
                            case "hidden":
                            case "number":
                            case "password":
                            case "text":
                                $(this).attr("readonly", disabled);
                                break;
                        }
                });
            };

            console.log(params);

            //prepare elements to wait the request
            disabledElements(true);
            
            $.post(action, params, function(response){
                disabledElements(false);
                
                typeof callback === "function" && callback(response);
            }, dataType)
            .fail(function(xhr, errorType, errorMessage){
                console.log(errorMessage);
                console.log(xhr.responseText);

                switch(errorType){
                    case "parsererror":
                        console.log("Error in parser, type " + dataType);
                        break;
                }
            });
        }
        
        return this;
    };
    
    $.fn.goTo = function() {
        $('html, body').animate({
            scrollTop: $(this).offset().top + 'px'
        }, 'fast');
        
        return this;
    };
}(jQuery));