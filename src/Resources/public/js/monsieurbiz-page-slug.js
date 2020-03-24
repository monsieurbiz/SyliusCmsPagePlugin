document.addEventListener('DOMContentLoaded', () => {
    (function ($) {
        'use strict';

        $.fn.extend({
            mbizPageSlugGenerator: function () {
                let timeout;

                $('[name*="monsieurbiz_cms_page[translations]"][name*="[title]"]').on('input', function () {
                    clearTimeout(timeout);
                    let element = $(this);

                    timeout = setTimeout(function () {
                        updateSlug(element);
                    }, 1000);
                });

                $('.toggle-page-slug-modification').on('click', function (e) {
                    e.preventDefault();
                    toggleSlugModification($(this), $(this).siblings('input'));
                });

                function updateSlug(element) {
                    let slugInput = element.parents('.content').find('[name*="[slug]"]');
                    let loadableParent = slugInput.parents('.field.loadable');

                    if ('readonly' === slugInput.attr('readonly')) {
                        return;
                    }

                    loadableParent.addClass('loading');

                    $.ajax({
                        type: "GET",
                        url: slugInput.attr('data-url'),
                        data: {title: element.val()},
                        dataType: "json",
                        accept: "application/json",
                        success: function (data) {
                            slugInput.val(data.slug);
                            if (slugInput.parents('.field').hasClass('error')) {
                                slugInput.parents('.field').removeClass('error');
                                slugInput.parents('.field').find('.sylius-validation-error').remove();
                            }
                            loadableParent.removeClass('loading');
                        }
                    });
                }

                function toggleSlugModification(button, slugInput) {
                    if (slugInput.attr('readonly')) {
                        slugInput.removeAttr('readonly');
                        button.html('<i class="unlock icon"></i>');
                    } else {
                        slugInput.attr('readonly', 'readonly');
                        button.html('<i class="lock icon"></i>');
                    }
                }
            }
        });
    })(jQuery);

    (function ($) {
        $(document).ready(function () {
            $(this).mbizPageSlugGenerator();
        });
    })(jQuery);
});
