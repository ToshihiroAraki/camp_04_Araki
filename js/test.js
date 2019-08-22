// ヘッダーの動き
$(function() {
    var nav = $('.dropdown');
    $('li', nav)
    .mouseover(function(e) {
    $('ul', this).stop().slideDown('fast');
    })
    .mouseout(function(e) {
    $('ul', this).stop().slideUp('fast');
    });
    });

//inputのタブの動き
jQuery(function($){
    $('.tab').click(function(){
        $('.is-active').removeClass('is-active');
        $(this).addClass('is-active');
        $('.is-show').removeClass('is-show');
        // クリックしたタブからインデックス番号を取得
        const index = $(this).index();
        // クリックしたタブと同じインデックス番号をもつコンテンツを表示
        $('.panel').eq(index).addClass('is-show');
    });
}); 

//pass忘れメッセージ
$('.text').on('click', function() {
  alert("システム管理者にお問い合わせ下さい（内線:1234）");
});
$('.text').hover(function() {
    $('.text').css('cursor','pointer');
  });

//印刷button
$('.insatu').on('click', function() {
    window.print();
  });
