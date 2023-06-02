<?php
/** @var \yii\web\View $this */
/** @var string $typeMeeting */

$this->registerJs(<<<JS
    
    (function(){
                
        $('.$typeMeeting-index [data-bs-toggle="tooltip"]').tooltip()
        $('.$typeMeeting-index [data-bs-toggle="tooltip"]').on('click', function(){
            $(this).tooltip('hide')
        })

        const modalViewerMeeting = new ModalViewer()
        $(modalViewerMeeting).on('onRequestJsonAfterAutoCloseModal', function(){
            setTimeout(() => {
                $.pjax.reload({ container: '#pjax-meeting-$typeMeeting-index' })
            }, 300)            
        })

        // create
        $('.$typeMeeting-index #btn-create').on('click', function(){
            modalViewerMeeting.showModal($(this).attr('href'))
            return false
        })

        // update
        $('.$typeMeeting-index .btn-update').on('click', function(){
            modalViewerMeeting.showModal($(this).attr('href'))
            return false
        })

        // delete
        $('.$typeMeeting-index .btn-delete').on('click', function(){        
            if (!confirm('Вы уверены, что хотите удалить?')) {
                return false
            }    
            const btn = $(this)
            btn.prop('disabled', true)
            btn.append(' <span class="spinner-border spinner-border-sm"></span>')

            $.post(btn.data('url'))
            .done(function() {
                $.pjax.reload({ container: '#pjax-meeting-$typeMeeting-index' })
            })
            .always(function() {
                btn.prop('disabled', false)
                btn.children('span').remove()
            })
            return false
        })

        setTimeout(() => {
            $.pjax.reload({ container: '#pjax-meeting-$typeMeeting-index', withoutLoader: true, timeout: false })
        }, 1000 * 60)

    }())
    
JS); 
