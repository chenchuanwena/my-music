var playId = false, imgurl, musicData, $this, $parent, $playObj = false;
$(document).ready(function () {
    var playMode = JY.getCookie('playStatus');
    var timer = '';
    var cacheList = {};
    //初始化------------
    JY.player = $("#JYplayer").jPlayer({
        swfPath: JY.PUBLIC + '/static/js/jPlayer',	//swfUrl,
        volume: 0.7,
        //id:'jp_container';
        supplied: "mp3,m4a",
        wmode: "window",
        cssSelectorAncestor: "#jy-player",
        keyEnabled: true,
        remainingDuration: true,
        toggleDuration: true,
        loop: false,
        ready: function () {
            //console.log(playMode);
            if (playMode != null) {
                playMode['player'] = parseInt(playMode['player']) + 1
                playMode['stop'] = 0;
            } else {
                playMode = {player: 1, stop: 0};
            }
            JY.setCookie('playStatus', playMode);
        },
        timeupdate: function (event) {
            time = event.jPlayer.status.currentTime;
        },
        ended: function () {//设置循环播放
            if ($('.jp-repeat').is(':visible')) {
                var obj = $('.p-active').next();
                if (obj.length > 0) {
                    //$('.jp-play-me').removeClass('active');
                    obj.find('.jp-play-me').trigger('click');
                } else {
                    var $parent = $('.p-active').parent();
                    $parent.find('li:first').find('a.jp-play-me').trigger('click');
                }
            } else {
                $this.trigger('click');
            }
        }
    });


    var listWramp = $('#play-list-wramp');
    var listCon = $('#play_lists');
    var playerWramp = $('#footer-player');
    var lockbnt = $('#player-dw');
    var lrcWramp = $('#lrc-wramp');
    //播放事件
    $(document).on($.jPlayer.event.play, $.jPlayer.cssSelector, function () {
        $this.addClass('active');
        $this.parents('li').removeClass('pause');
        playMode['stop'] = 1;
        JY.setCookie('playStatus', playMode);
        infoAlert('已开始播放', 1);
        listCon.find('li').removeClass('playing');
        listCon.find('[data-id=' + playId + ']').addClass('playing');

    });

    //暂停事件
    $(document).on($.jPlayer.event.pause, $.jPlayer.cssSelector, function () {
        $this.removeClass('active');
        $this.parents('li').addClass('pause');
        playMode['stop'] = 0;
        JY.setCookie('playStatus', playMode);

    });

    //下一曲
    $(document).on('click', '.jp-next', function () {
        if ($('.jp-repeat').is(':visible')) {
            $('.p-active').next().find('.jp-play-me').trigger('click');
        } else {
            $this.trigger('click');
        }
    });
    //上一曲
    $(document).on('click', '.jp-previous', function () {
        if ($('.jp-repeat').is(':visible')) {
            $('.p-active').prev().find('.jp-play-me').trigger('click');
        } else {
            $this.trigger('click');
        }
    });


    $(document).on('click', '.jp-play-me', function (e) {
        e && e.preventDefault();
        $this = $(this);
        var num = $this.find('.num').html();
        $this.find('.num').html(Number(num) + 1);
        //获取歌曲Id
        playId = $(this).attr('data-id');

        var song = cacheList[playId];
        if (typeof(song) == "undefined") {
            $.post(U("Music/getData"), {"id": playId}, function (song) {
                ;
                if (song) {
                    cacheList[playId] = song;
                    playSong(song);
                }
            }, "json");
        } else {
            playSong(song);
        }

        if (!$this.is('a')) $this = $this.closest('a');
        if ($this.parents('li').length > 0) {
            $parent = $this.parents('li')
        } else {
            $parent = $this.parents('.play_box')
        }
        $('.p-active').removeClass('p-active');
        $parent.addClass('p-active');
        $('.jp-play-me').not($this).removeClass('active');
        $this.toggleClass('active');
        //弹出播放器
        $('#footer-player').animate({bottom: 0}, 600);
    });

    /*专辑播放*/
    $('.album_play').click(function () {
        var album_id = $(this).attr('data-id');
        $.post(U("/Music/albumSongs"), {"id": album_id}, function (data) {
            ;
            if (data) {
                var html = '';
                var count = data.length;
                for (i = 0; i < count; i++) {
                    html += makeList(data[i]);
                }
            }
            playId = data[0]['id'];
            listCon.prepend(html);
            listCon.find('[data-id=' + playId + ']').click();
        }, "json");
        return false;
    })


    //播放器隐藏/显示
    lockbnt.click(function () {
        if (lockbnt.hasClass('on')) {
            lockbnt.html('<i class="jy jy-unlock-f"></i>').removeClass('on');
        } else {
            lockbnt.html('<i class="jy jy-lock-f"></i>').addClass('on');
        }
        return false;
    });

    playerWramp.hover(
        function () {
            playerWramp.stop().animate({bottom: 0}, 600);
        },
        function () {
            if (!lockbnt.hasClass('on') && !lrcWramp.hasClass('on') && !listWramp.hasClass('on')) {
                playerWramp.stop().animate({bottom: -52}, 600);
            }
        }
    );

    $('#lrc-btn').click(function () {
        if (lrcWramp.hasClass('on')) {
            lrcWramp.removeClass('on').hide();
        } else {
            lrcWramp.addClass('on').show();
        }
    });

    $('#l-close').click(function () {
        lrcWramp.removeClass('on').hide();
    });

    $('#list-btn').click(function () {
        if (listWramp.hasClass('on')) {
            listWramp.removeClass('on').hide();
        } else {
            listWramp.addClass('on').show();
        }
    });

    $('#pl-close').click(function () {
        listWramp.removeClass('on').hide();
    });


    timer = setInterval(function () {
        var playStatus = JY.getCookie('playStatus');
        //console.log(playMode);
        if (playStatus != null && parseInt(playStatus['stop']) && playMode['player'] != parseInt(playStatus['player'])) {
            JY.player.jPlayer("pause");
        }
    }, 1000);


    window.onbeforeunload = function onbeforeunload_handler() {
        var playStatus = JY.getCookie('playStatus');
        playStatus['player'] = parseInt(playStatus['player']) - 1;
        JY.setCookie('playStatus', playStatus);
    }

    function playSong(song) {
        JY.player.jPlayer("setMedia", {mp3: song.listen_url, title: song.artist_name + ' - ' + song.name});
        JY.player.jPlayer("play");
        $('#play-cover').attr('src', song.cover_url);
        var lrc = song.lrc;
        $('#l-title').html(song.name);
        if ($.type(lrc) != 'null') {
            $('#lrc_list').html('歌词加载中....');
            $('.lrc-content').text(lrc);
            $.lrc.start(lrc, function () {
                return time;
            });
        } else {
            $('#lrc_list').html('<li>没有找到相关歌词....</li>');
            $.lrc.stop();
        }
        if (listCon.find('[data-id=' + playId + ']').length < 1) {
            listCon.prepend(makeList(song));
        }
    }
});

/*创建播放列表*/

function makeList(song) {
    var $li = '<li class="jp-play-me" data-id="' + song['id'] + '">' +
        '<div class="play_icon"><i class="jy jy-play"></i></div>' +
        '<div class="play_name">' + song['name'] + '</div>' +
        '<div class="play_aname">' + song['artist_name'] + '</div>' +
        '<div class="play_del">' +
        '<!--a class="del-song" href="javascript:;" ><i class="jy jy-trash"></i></a-->' +
        '</div>' +
        '</li>';
    return $li;
}


/*歌词插件*/
(function (d) {
    d.lrc = {
        handle: null,
        list: [],
        regex: /^[^\[]*((?:\s*\[\d+\:\d+(?:\.\d+)?\])+)([\s\S]*)$/,
        regex_time: /\[(\d+)\:((?:\d+)(?:\.\d+)?)\]/g,
        regex_trim: /^\s+|\s+$/,
        callback: null,
        interval: .3,
        format: "\x3cli\x3e{html}\x3c/li\x3e",
        prefixid: "lrc",
        hoverClass: "hover",
        hoverTop: 100,
        duration: 0,
        __duration: -1,
        start: function (a, e) {
            if (!("string" != typeof a || 1 > a.length || "function" != typeof e)) {
                this.stop();
                this.callback = e;
                var c = null, f = null, l = "";
                a = a.split("\n");
                for (var b = 0; b < a.length; b++)if (c = a[b].replace(this.regex_trim, ""), !(1 > c.length) && (c = this.regex.exec(c))) {
                    for (; f = this.regex_time.exec(c[1]);)this.list.push([60 * parseFloat(f[1]) + parseFloat(f[2]), c[2]]);
                    this.regex_time.lastIndex = 0
                }
                if (0 < this.list.length) {
                    this.list.sort(function (a, c) {
                        return a[0] - c[0]
                    });
                    .1 <= this.list[0][0] && this.list.unshift([this.list[0][0] - .1, ""]);
                    this.list.push([this.list[this.list.length - 1][0] + 1, ""]);
                    for (b = 0; b < this.list.length; b++)l += this.format.replace(/\{html\}/gi, this.list[b][1]);
                    d("#" + this.prefixid + "_list").html(l).animate({marginTop: 0}, 100).show();
                    d("#" + this.prefixid + "_nofound").hide();
                    this.handle = setInterval("$.lrc.jump($.lrc.callback());", 1E3 * this.interval)
                } else d("#" + this.prefixid + "_list").hide(), d("#" + this.prefixid + "_nofound").show()
            }
        },
        jump: function (a) {
            if ("number" != typeof this.handle || "number" != typeof a || !d.isArray(this.list) || 1 > this.list.length)return this.stop();
            0 > a && (a = 0);
            if (this.__duration != a) {
                this.__duration = a += .2;
                a += this.interval;
                var e = 0, c = this.list.length - 1;
                pivot = Math.floor(c / 2);
                tmpobj = null;
                tmp = 0;
                for (thisobj = this; e <= pivot && pivot <= c && !(this.list[pivot][0] <= a && (pivot == c || a < this.list[pivot + 1][0]));) {
                    this.list[pivot][0] > a ? c = pivot : e = pivot;
                    tmp = e + Math.floor((c - e) / 2);
                    if (tmp == pivot)break;
                    pivot = tmp
                }
                pivot != this.pivot && (this.pivot = pivot, tmpobj = d("#" + this.prefixid + "_list").children().removeClass(this.hoverClass).eq(pivot).addClass(thisobj.hoverClass), tmp = tmpobj.next().offset().top - tmpobj.parent().offset().top - this.hoverTop, tmp = 0 < tmp ? -1 * tmp : 0, this.animata(tmpobj.parent()[0]).animate({marginTop: tmp + "px"}, 1E3 * this.interval))
            }
        },
        stop: function () {
            "number" == typeof this.handle && clearInterval(this.handle);
            this.handle = this.callback = null;
            this.__duration = -1;
            this.regex_time.lastIndex = 0;
            this.list = []
        },
        animata: function (a) {
            var e = j = 0, c, d = {
                execution: function (d, b, f) {
                    var h = (new Date).getTime(), k = f || 500, m = parseInt(a.style[d]) || 0, n = b - m;
                    (function () {
                        var b = (new Date).getTime() - h;
                        if (b > k) {
                            var b = k, f = a.style, g = b, b = -n * (g /= k) * (g - 2) + m;
                            f[d] = b + "px";
                            ++e == j && c && c.apply(a);
                            return !0
                        }
                        f = a.style;
                        g = b;
                        b = -n * (g /= k) * (g - 2) + m;
                        f[d] = b + "px";
                        setTimeout(arguments.callee, 10)
                    })()
                }, animate: function (a, b, e) {
                    c = e;
                    for (var h in a)j++, d.execution(h, parseInt(a[h]), b)
                }
            };
            return d
        }
    }
})(jQuery);



