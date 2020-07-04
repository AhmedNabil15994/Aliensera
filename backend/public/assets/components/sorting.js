var elemIndex = 0;
var elemIndex2 = 0;
$( function() {

    $("#accordion").sortable({
        group: 'simple_with_animation',
        pullPlaceholder: false,
        handle: 'i.fa.fa-arrows-alt.first',
        // set $item relative to cursor position
        onDragStart: function ($item, container, _super) {
            var offset = $item.offset();
            elemIndex = $item.index() + 1;
            pointer = container.rootGroup.pointer;
            adjustment = {
                left: pointer.left - offset.left,
                top: pointer.top - offset.top
            };

            _super($item, container);
        },
        onDrag: function ($item, position) {
            $item.css({
                left: position.left - adjustment.left,
                top: position.top - adjustment.top
            });
        },
        onDrop: function  ($item, container, _super) {
            var $clonedItem = $('<li/>').css({height: 0});
            $item.before($clonedItem);
            if($item.index() != 0 && $item.index() != elemIndex){
                var url = window.location.href;
                if(url.indexOf("#") != -1){
                    url = url.replace('#','');
                }

                var ids = [];
                var indexes = [];
                $('#accordion li.panel').each(function(index,elem){
                    ids.push($(elem).data('tab'));
                    indexes.push(index);
                });

                var myURL = url+'/sortLesson';
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    type:'post',
                    url: myURL,
                    data:{
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'ids': JSON.stringify(ids),
                        'sorts': JSON.stringify(indexes),
                    },
                });     
            }
            $clonedItem.animate({'height': $item.height()});

            $item.animate($clonedItem.position(), function  () {
                $clonedItem.detach();
                _super($item, container);
            });
        },
    });

    $(".playlist").sortable({
        group: 'no-drop',
        containerSelector: '.playlist',
        itemSelector: '.row.results',
        handle: 'i.fa.fa-arrows-alt.second',
        // set $item relative to cursor position
        onDragStart: function ($item, container, _super,event) {
            elemIndex2 = $item.index();
            event.stopPropagation();
            event.preventDefault();
            _super($item, container);
        },
        onDrop: function  ($item, container, _super,event) {
            event.stopPropagation();
            event.preventDefault();
            if(!container.options.drop)
              $item.clone().insertAfter($item);
            _super($item, container);
            if($item.index() != 0 && $item.index() != elemIndex2){
                var url = window.location.href;
                if(url.indexOf("#") != -1){
                    url = url.replace('#','');
                }

                var ids2 = [];
                var indexes2 = [];
                $item.parent('.playlist').children('.row.results').each(function(index,elem){
                    ids2.push($(elem).data('tab'));
                    indexes2.push(index);
                });
                var myURL = url+'/sortVideo';
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $.ajax({
                    type:'post',
                    url: myURL,
                    data:{
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'lesson_id': $('.panel-collapse.collapse.in').parent('li.panel').data('tab'),
                        'ids': JSON.stringify(ids2),
                        'sorts': JSON.stringify(indexes2),
                    },
                });     
            }
        },
    });
});