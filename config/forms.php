<?php

    function build_input($id, $name, $type='text', $class='w-50', $value='', $placeholder='') {
        return array(
            "id" => $id,
            "name" => $name,
            "type" => $type,
            "class" => $class,
            "value" => clearString($value),
            "placeholder" => clearString($placeholder)
        );
    }

    function create_form_group($label_title, $label_class='', $input_data, $block_class='', $button=[]) {
        $form_id = $input_data['id'];
        $input_type = $input_data['type'];
        $input_class = $input_data['class'];
        $form_name = $input_data['name'];
        $form_value = $input_data['value'];
        $form_placeholder = $input_data['placeholder'];
        echo "
            <div class='form-group $block_class'>
                <label class='$label_class' for='$form_id'>$label_title</label>
                <input class='form-control $input_class' type='$input_type' id='$form_id' name='$form_name' ng-model='$form_id' placeholder='$form_placeholder' value='$form_value'>
            </div>
        ";
        if (count($button) > 0) {
            $random_id = randomValue(5);
            $id_btn = @$button['id'] ? $button['id'] : $random_id;
            $class_btn = @$button['class'] ? $button['class'] : 'btn btn-primary';
            $type_btn = @$button['type'] ? $button['type'] : 'button';
            $value_btn = @$button['value'] ? $button['value'] : 'Button';
            echo "
                <div class='form-group $block_class'>
                    <button type='$type_btn ' id='$id_btn' class='$class_btn'>$value_btn</button>
                </div>
            ";
        }
    }

    function create_form_group_align($label_title, $label_class='', $input_data, $block_class='', $block_sect1='', $block_sect2 = '', $button=[]) {
        $form_id = $input_data['id'];
        $input_type = $input_data['type'];
        $input_class = $input_data['class'];
        $form_name = $input_data['name'];
        $form_value = $input_data['value'];
        $form_placeholder = $input_data['placeholder'];
        echo "
            <table class='table table-borderless $block_class'>
                <tr>
                    <td class='$block_sect1'><label class='$label_class' for='$form_id'>$label_title</label></td>
                    <td class='$block_sect2'><input class='form-control $input_class' type='$input_type' id='$form_id' name='$form_name' ng-model='$form_id' placeholder='$form_placeholder' value='$form_value'></td>
                </tr>
        ";
        if (count($button) > 0) {
            $random_id = randomValue(5);
            $id_btn = @$button['id'] ? $button['id'] : $random_id;
            $class_btn = @$button['class'] ? $button['class'] : 'btn btn-primary';
            $type_btn = @$button['type'] ? $button['type'] : 'button';
            $align_btn = @$button['align'] ? $button['align'] : 'left';
            $value_btn = @$button['value'] ? $button['value'] : 'Button';
            echo "
                <tr>
                    <td align='$align_btn' colspan='2'><button type='$type_btn ' id='$id_btn' class='$class_btn'>$value_btn</button></td>
                </tr>
            </table>
            ";
        } else {
            echo "</table>";
        }
    }

    function build_forms_groups($data, $button=[]) {
        foreach ($data as $row) {
            $block_class = @$row['block_class'] ? $row['block_class'] : '';
            $label_class = @$row['label_class'] ? $row['label_class'] : '';
            create_form_group($row['label'], $label_class, $row['input_data'], $block_class);
        }
        if (count($button) > 0) {
            $random_id = randomValue(5);
            $id_btn = @$button['id'] ? $button['id'] : $random_id;
            $class_btn = @$button['class'] ? $button['class'] : 'btn btn-primary';
            $type_btn = @$button['type'] ? $button['type'] : 'button';
            $value_btn = @$button['value'] ? $button['value'] : 'Button';
            echo "
                <div class='form-group $block_class'>
                    <button type='$type_btn ' id='$id_btn' class='$class_btn'>$value_btn</button>
                </div>
            ";
        }
    }

    function build_forms_groups_align($data, $block_class='', $button=[]) {
        echo "<table class='table table-borderless $block_class'>";
        foreach ($data as $row) {
            $block_sect1 = @$row['block_sect1'] ? $row['block_sect1'] : '';
            $block_sect2 = @$row['block_sect2'] ? $row['block_sect2'] : '';
            $input_class = @$row['input_class'] ? $row['input_class'] : 'w-50';
            $label_class = @$row['label_class'] ? $row['label_class'] : '';
            $label_title = $row['label'];
            $input_type = $row['input_data']['type'];
            $form_name = $row['input_data']['name'];
            $form_id = $row['input_data']['id'];
            $form_value = $row['input_data']['value'];
            $form_placeholder = $row['input_data']['placeholder'];
            echo "
                <tr>
                    <td class='$block_sect1'><label class='$label_class' for='$form_id'>$label_title</label></td>
                    <td class='$block_sect2'><input class='form-control $input_class' type='$input_type' id='$form_id' name='$form_name' ng-model='$form_id' placeholder='$form_placeholder' value='$form_value'></td>
                </tr>
            ";
        }
        if (count($button) > 0) {
            $random_id = randomValue(5);
            $id_btn = @$button['id'] ? $button['id'] : $random_id;
            $class_btn = @$button['class'] ? $button['class'] : 'btn btn-primary';
            $type_btn = @$button['type'] ? $button['type'] : 'button';
            $align_btn = @$button['align'] ? $button['align'] : 'left';
            $value_btn = @$button['value'] ? $button['value'] : 'Button';
            echo "
                    <tr>
                        <td align='$align_btn' colspan='2'><button type='$type_btn ' id='$id_btn' class='$class_btn'>$value_btn</button></td>
                    </tr>
                </table>
                ";
        } else {
            echo "</table>";
        }
    }

    function create_input($input_data) {
        $form_id = $input_data['id'];
        $input_type = $input_data['type'];
        $input_class = $input_data['class'];
        $form_name = $input_data['name'];
        $form_value = $input_data['value'];
        $form_placeholder = $input_data['placeholder'];
        echo "
            <input class='form-control $input_class' type='$input_type' id='$form_id' name='$form_name' ng-model='$form_id' placeholder='$form_placeholder' value='$form_value'>
        ";
    }

    function submit_button($value_btn, $id_btn, $class_btn, $block_class='') {
        echo "
            <div class='form-group $block_class'>
                <button type='submit' id='$id_btn' class='$class_btn'>$value_btn</button>
            </div>
        ";
    }

?>