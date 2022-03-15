/**
 * This file is part of the MailWizz EMA application.
 * 
 * @package MailWizz EMA
 * @author MailWizz Development Team <support@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.4.5
 */
jQuery(document).ready(function($){

    $(document).on('click', 'a.copy-server, a.enable-server, a.disable-server', function() {
		$.post($(this).attr('href'), ajaxData, function(){
			window.location.reload();
		});
		return false;
	});
    
	(function(){
        var segmentConditionsIndex = 10000 + 1 * $('.conditions-container .item').length,
            $segmentConditionsTemplate = $('#condition-template');

        $('.btn-add-condition').on('click', function(){
            var html = $segmentConditionsTemplate.html();
            html = html.replace(/\{index\}/g, segmentConditionsIndex);
            $('.conditions-container').append(html);
            ++segmentConditionsIndex;
            return false;
        });

        $(document).on('click', '.btn-remove-condition', function(){
            $(this).closest('.item').remove();
            return false;
        });
	})();
});