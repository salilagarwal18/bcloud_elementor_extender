jQuery(document).ready(bcloud_calculator_init);

function bcloud_calculator_init(){
    const $ = jQuery;
    console.log($('.bcloud-calculator-field').val());
    $('.bcloud-calculator-field').each(function(){
        bcloud_calculator_field($(this));
    });

}

function bcloud_calculator_field(calculator_field){
    const $ = jQuery; 
    var formula = $(calculator_field).attr('data-formula');
    var formula_parts = formula.split(' ');
    formula_parts = bcloud_remove_empty_elements(formula_parts)
    console.log(formula_parts)
    formula_parts.forEach(function(formula_part){
        if (formula_part == '(' || formula_part == ')'){}
        else if ($('#form-field-' + formula_part).length){
            $('#form-field-' + formula_part).on('input', function(){
                bcloud_calculator_update_value($(this), $);
            });
            let attr_value = $('#form-field-' + formula_part).attr('data-bcloud-update-field-id');
            if (attr_value){
                // TO DO For multiple calculator fields in the same form
            }
            else{
                $('#form-field-' + formula_part).attr('data-bcloud-update-field-id', $(calculator_field).attr('id'));
            }
        }
    })
    $(calculator_field).val(23);
}


function bcloud_remove_empty_elements(any_array){
    var new_array = []
    any_array.forEach(function(element){
        if (element == ''){}
        else{
            new_array.push(element)
        }
    })
    return new_array
}

function bcloud_calculator_update_value(selected_obj, $){
    var update_field_id = $(selected_obj).attr('data-bcloud-update-field-id');
    var formula = $('#' + update_field_id).attr('data-formula');
    var formula_parts = formula.split(' ');
    var result =  bcloud_calculator_parse_parenthesis(formula_parts, $)
    formula = $('#' + update_field_id).val(result);
}


function bcloud_calculator_parse_parenthesis(formula_parts){
    formula_parts = bcloud_remove_empty_elements(formula_parts)
    var parenthesis_found = false;
    var index_of_opening_paren = -1
    var index_of_closing_paren = -1
    var final_formula_parts = []
    do {
        parenthesis_found = false
        final_formula_parts = []
        var found_one_closing_paren = false
        formula_parts.forEach(function(formula_part, index){
            final_formula_parts.push(formula_part)
            if(formula_part == '('){
                parenthesis_found = true
                index_of_opening_paren = index
            }
            else if (formula_part == ')' && !found_one_closing_paren){
                
                index_of_closing_paren = index
                console.log(index_of_opening_paren)
                console.log(index_of_closing_paren)
                console.log(formula_parts.slice(index_of_opening_paren + 1, index_of_closing_paren))
                var result = bcloud_calculator_get_new_value(formula_parts.slice(index_of_opening_paren + 1, index_of_closing_paren))
                final_formula_parts.splice(index_of_opening_paren, (index_of_closing_paren - index_of_opening_paren) + 1)
                final_formula_parts.push(result)
                console.log(final_formula_parts)
                found_one_closing_paren = true

            }
        })
        console.log(final_formula_parts)
        formula_parts = final_formula_parts
    }while(parenthesis_found)
    console.log(formula_parts)
    return bcloud_calculator_get_new_value(formula_parts)
}

function bcloud_calculator_get_new_value(formula_parts){
    let result = null
    let operand1 = null, operand2 = null, operator = null
    formula_parts.forEach(function(formula_part){
        if (jQuery('#form-field-' + formula_part).length){
            if (!operand1){
                operand1 = jQuery('#form-field-' + formula_part).val();
                if (isNaN(operand1)){
                    operand1 = 0;
                }            
            }
            else {
                operand2 = jQuery('#form-field-' + formula_part).val();
                if (isNaN(operand2)){
                    operand2 = 0;
                }
                result = bcloud_calculator_do_math(operand1, operand2, operator)
                if (result){
                    operand1 = result
                }
                operand2 = null
                operator = null
            }
        }
        else if ( !isNaN( formula_part ) ){
            if (!operand1){
                operand1 = Number(formula_part);
            }
            else {
                operand2 = Number(formula_part);
                result = bcloud_calculator_do_math(operand1, operand2, operator)
                if (result){
                    operand1 = result
                }
                operand2 = null
                operator = null
            }
        }
        else {
            switch (formula_part){
                case '+':
                    operator = '+';
                    break;
                case '-':
                    operator = '-';
                    break;
                case '*':
                    operator = '*';
                    break;
                case '/':
                    operator = '/';
                    break;
                case '%':
                    operator = '%';
                    break;
            }
        }
    })
    return result;
}


function bcloud_calculator_do_math(operand1, operand2, operation){
    switch (operation){
        case '+':
            return Number(operand1) + Number(operand2)
        case '-':
            return Number(operand1) - Number(operand2)
        case '*':
            return Number(operand1) * Number(operand2)
        case '/':
            if (Number(operand2) == 0){
                return 0
            }
            return Number(operand1) / Number(operand2)
        case '%':
            return Number(operand1) % Number(operand2)
        default:
            return 0;
    }
}