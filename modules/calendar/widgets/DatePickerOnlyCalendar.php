<?php
namespace app\modules\calendar\widgets;

/**
 * Datepicker только в виде календаря, без input
 * @author toatall
 */
class DatePickerOnlyCalendar extends \kartik\date\DatePicker
{

    /**
     * {@inheritdoc}
     */
    protected function renderInput()
    {
        return $this->parseMarkup($this->getInput('hiddenInput'));
    }

}
