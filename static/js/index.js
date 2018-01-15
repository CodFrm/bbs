"use strict";
/**
 * 简单的图片轮播
 */
$.fn.carousel = function (param) {
    var images = param['images'];
    var pages = '<div class="car-title">' +
        '<span></span><div class="car-page">';
    for (var i = 0; i < images.length; i++) {
        if (images[i].src != undefined) {
            var html = '<a class="car-item" href="' + (images[i].href == undefined ? '#' : images[i].href) + '"><img class="car-img" src="' + images[i].src + '"/></a>';
            pages += '<div class="car-page-item">';
            pages += '</div>';
            $(this).append(html);
        }

    }
    pages += '</div></div>';
    $(this).prepend(pages);
    var fobj = this;
    var lastPage = -1;
    var nowPage = 0;
    this.selectPage = function (page) {
        $(fobj).find('.car-item:eq(' + lastPage.toString() + ')').css({
            'opacity': 0
        });
        $(fobj).find('.car-item:eq(' + page + ')').css({
            'opacity': 1
        });
        $(fobj).find('.car-page-item:eq(' + lastPage.toString() + ')').css({
            'background': '#ffffff'
        });
        $(fobj).find('.car-page-item:eq(' + page + ')').css({
            'background': '#82BFEB'
        });
        if (param['images'][page].title != undefined) {
            $(fobj).find('.car-title>span').html(param['images'][page].title);
        } else {
            $(fobj).find('.car-title>span').html('');
        }
        lastPage = page;
        nowPage = (page >= images.length - 1 ? 0 : page + 1);
    }
    var Timer = setInterval(function () {
        fobj.selectPage(nowPage);
    }, 4000);
    this.selectPage(0);
    $(this).on('click', '.car-page>.car-page-item', function (e, a, b) {
        clearInterval(Timer);
        Timer = setInterval(function () {
            fobj.selectPage(nowPage);
        }, 4000);
        var index = $(fobj).find('.car-title>.car-page>.car-page-item').index(this);
        fobj.selectPage(index);
    });
    return this;
}

/**
 * 页面弹出信息框
 */
window.MessageBox = {
    prompt: function (msgStr, icon) {
        var prompt = {},
            call = {};
        icon = icon || 'success';
        var html = '<div class="message-box prompt"><i class="prompt-icon iconfont icon-' + icon + '"></i>';
        html += '<span>' + msgStr + '</span></div>';
        prompt.success = function (func) {
            call.success = func;
            return prompt;
        }
        $('body').append(html);
        $('.message-box.prompt').css({
            'width': ($('.message-box.prompt').width() + 20) + 'px',
            'right': 0
        });
        setTimeout(function () {
            $('.message-box.prompt').css({
                'opacity': 1,
            });
        }, 100);
        setTimeout(function () {
            if (call.success != undefined) call.success();
            $('.message-box.prompt').css({
                'opacity': 0
            });
            setTimeout(function () {
                $('.message-box.prompt').remove();
            }, 1000);
        }, 3000);
        return prompt;
    }
}