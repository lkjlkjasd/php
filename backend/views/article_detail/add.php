<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'content')->textInput();
echo '<button type="submit" class="btn btn-block">提交</button>';
\yii\bootstrap\ActiveForm::end();