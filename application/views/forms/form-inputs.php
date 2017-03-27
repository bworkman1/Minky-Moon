<?php
if(!empty($inputs)) {
    foreach($inputs as $key => $val) {
        switch($val['input_type']) {
            case 'text':
                echo '<div class="'.$val['input_columns'].' grid-stack formInputObject" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'" data-id="'.$val['id'].'">';
                echo '<div class="form-group">';
                $required = '';
                if (strpos($val['input_validation'], 'required') !== false) {
                    $required = '<span class="text-danger">*</span> ';
                }
                echo '<label>'.$required.$val['input_label'].'</label>';
                echo '<input class="'.$val['custom_class'].' form-control" name="'.$val['input_name'].'"/>';
                echo '</div>';
                if($val['encrypt_data']) {
                    echo '<i class="encryptedIcon fa fa-lock"></i>';
                }
                echo '</div>';
                break;

            case 'select':
                echo '<div class="'.$val['input_columns'].' grid-stack formInputObject" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'" data-id="'.$val['id'].'">';
                echo '<div class="form-group">';
                $required = '';
                if (strpos($val['input_validation'], 'required') !== false) {
                    $required = '<span class="text-danger">*</span> ';
                }
                echo '<label>'.$required.$val['input_label'].'</label>';
                echo '<select class="'.$val['custom_class'].' form-control" name="'.$val['input_name'].'">';
                echo '<option value="">Select One</option>';
                foreach($val['options'] as $option) {
                    echo '<option  value="'.$option['value'].'">'.$option['name'].'</option>';
                }
                echo '</select>';
                echo '</div>';
                if($val['encrypt_data']) {
                    echo '<i class="encryptedIcon fa fa-lock"></i>';
                }
                echo '</div>';
                break;

            case 'textarea':
                echo '<div class="'.$val['input_columns'].' grid-stack formInputObject" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'" data-id="'.$val['id'].'">';
                echo '<div class="form-group">';
                echo '<label>'.$val['input_label'].'</label>';
                echo '<textarea class="'.$val['custom_class'].' form-control" name="'.$val['input_name'].'"></textarea>';
                echo '</div>';
                if($val['encrypt_data']) {
                    echo '<i class="encryptedIcon fa fa-lock"></i>';
                }
                echo '</div>';
                break;

            case 'checkbox':
                echo '<div class="'.$val['input_columns'].' grid-stack formInputObject" data-sequence="'.$val['sequence'].'" data-validation="'.$val['input_validation'].'" data-id="'.$val['id'].'">';
                $required = '';
                if (strpos($val['input_validation'], 'required') !== false) {
                    $required = '<span class="text-danger">*</span> ';
                }
                echo '<label>'.$required.$val['input_label'].'</label><br>';
                foreach($val['options'] as $option) {
                    $inline = 'checkbox';
                    if($val['input_inline']) {
                        $inline = 'checkbox-inline';
                    }
                    echo '<div class="'.$inline.'">';
                    echo '<label><input type="checkbox" class="' . $val['custom_class'] . '" name="' . $option['name'] . '[]" value="'.$option['value'].'"> ' . $option['name'] . '</label>';
                    echo '</div>';
                }
                if($val['encrypt_data']) {
                    echo '<i class="encryptedIcon fa fa-lock"></i>';
                }
                echo '</div>';

                break;
            default:
                break;
        }
    }
}
?>