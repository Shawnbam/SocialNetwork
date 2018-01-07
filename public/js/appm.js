var postId = 0;
var postBodyElement = null;
// $('.posts').on('click', '.post.interaction.edit', function(event) {
$('.posts').on('click', '.post .interaction .edit', function(event) {
    event.preventDefault();
    //var postBody = event.target.parentNode.parentNode.childNodes[1].textContent;
    postBodyElement = event.target.parentNode.parentNode.childNodes[1];
    var postBody = postBodyElement.textContent;
    postId = event.target.parentNode.parentNode.dataset['postid'];
    $('#post-body').val(postBody);
    $('#edit-modal').modal();
});

$('#modal-save').on('click', function () {
    $.ajax({
        method: 'POST',
        url: urlEdit,
        data: {body: $('#post-body').val(), postId: postId, _token : token}
    })
        .done(function (msg) {
            $(postBodyElement).text(msg['new_body']);
            $('#edit-modal').modal('hide');
    });
});

$('.like').on('click',function (event) {
    event.preventDefault();
    postId = event.target.parentNode.parentNode.dataset['postid'];
    var isLike = event.target.previousElementSibling == null;
    $.ajax({
        method: 'POST',
        url: urlLike,
        data: {isLike: isLike, postId: postId, _token: token }
    })
        .done(function (msg) {
            event.target.innerText = isLike ? event.target.innerText == 'Like' ? 'Liked' : 'Like' : event.target.innerText == 'Dislike' ? 'Disliked' : 'Dislike';
            if(isLike){
                event.target.nextElementSibling.innerHTML = 'Dislike';
            } else {
                event.target.previousElementSibling.innerHTML = 'Like';
            }

        })
});
