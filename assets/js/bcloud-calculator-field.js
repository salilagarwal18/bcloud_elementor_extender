jQuery(document).ready(bcloud_calculator_init);

function bcloud_calculator_init($){
    console.log($('.bcloud-calculator-field').val());
    $('.bcloud-calculator-field').each(function(){
        bcloud_calculator_field($(this), $);
    });

}

function bcloud_calculator_field(calculator_field, $){
    formula = $(calculator_field).attr('data-formula');
    formula_parts = formula.split(' ');
    console.log(formula_parts)
    formula_parts.forEach(function(formula_part){
        if ($('#form-field-' + formula_part).length){
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


function bcloud_calculator_update_value(selected_obj, $){
    update_field_id = $(selected_obj).attr('data-bcloud-update-field-id');
    formula = $('#' + update_field_id).attr('data-formula');
    formula_parts = formula.split(' ');
    result =  bcloud_calculator_get_new_value(formula_parts, $)
    formula = $('#' + update_field_id).val(result);
}

function bcloud_calculator_get_new_value(formula_parts, $){
    result = null
    let operand1 = null, operand2 = null, operator = null
    formula_parts.forEach(function(formula_part){
        if (formula_part == ''){
            
        }
        else if ($('#form-field-' + formula_part).length){
            if (!operand1){
                operand1 = $('#form-field-' + formula_part).val();
                if (isNaN(operand1)){
                    operand1  = 0;
                }            }
            else {
                operand2 = $('#form-field-' + formula_part).val();
                if (isNaN(operand2)){
                    operand2  = 0;
                }
                result = bcloud_calculator_do_math(operand1, operand2, operator)
                operand1 = null
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
                operand1 = null
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
    }
}