var ws = {};
var client_id = 0;
var userlist = {};
var GET = getRequest();
var face_count = 19;
var send_user_list = Array();
var allHistoryList = Array();
var HistoryList = Array();


$(document).ready(function () {


    //使用原生WebSocket
    if (window.WebSocket || window.MozWebSocket) {
        ws = new WebSocket(webim);
    }
    //使用flash websocket
    else if (webim.flash_websocket) {
        WEB_SOCKET_SWF_LOCATION = "/static/flash-websocket/WebSocketMain.swf";
        $.getScript("/static/flash-websocket/swfobject.js", function () {
            $.getScript("/static/flash-websocket/web_socket.js", function () {
                ws = new WebSocket(webim);
            });
        });
    }
    //使用http xhr长轮循
    else {
        ws = new Comet(webim);
    }
    initRemote();
    listenEvent();
});

function listenEvent() {
    /**
     * 连接建立时触发
     */
    ws.onopen = function (e) {
        //连接成功
        console.log("connect webim server success.");
        //发送登录信息
        msg = new Object();
        msg.cmd = 'login';
        msg.name = user.nickname;
        msg.userid = user.userid;
        msg.avatar = user.avatar;
        ws.send($.toJSON(msg));
    };

    //有消息到来时触发
    ws.onmessage = function (e) {
        var message = $.evalJSON(e.data);
        var cmd = message.cmd;
        if (cmd == 'login') {
            client_id = $.evalJSON(e.data).fd;
            // ws.send($.toJSON({cmd : 'getUnreadUser',userid:user.userid,parentid:msg.parent.userid}));
            //获取在线列表
            ws.send($.toJSON({cmd: 'getOnline', userid: user.userid}));
            //获取历史记录
            ws.send($.toJSON({cmd: 'getHistory', userid: user.userid}));
            //alert( "收到消息了:"+e.data );
        }
        else if (cmd == 'getOnline') {
            showOnlineList(message);
        }
        else if (cmd == 'getHistory') {
            showHistory(message);
            allHistoryList = message;
            initUsersHistory(message);
        }
        else if (cmd == 'newUser') {
            showNewUser(message);
            // showNewMsg(message);
        }
        else if (cmd == 'fromMsg') {
            $active_id = getActiveId();
            if ($active_id == ("inroom_" + message.from)|| $active_id=='inroom_0') {
                showNewMsg(message);
            } else {
                var oneHistory = new Object();
                oneHistory['from_userid'] = message['from'];
                message['channal'] = 10 + message['channal'];
                oneHistory['msg'] = message;
                oneHistory['to_userid'] = user.userid;
                oneHistory['to_username'] = user.username;
                oneHistory['type'] = message['type'];
                oneHistory['user'] = user;
                oneHistory['username'] = message['username'];
                seeMessage(message['from']);

                addHistoryList(oneHistory, 'from_userid');
            }

        }
        else if (cmd == 'offline') {
            var cid = message.fd;
            var userid = message.userid;
            delUser(cid, userid);
            //showNewMsg(message);
        }else if(cmd=='error'){
            layer.msg(message.msg);
        }
    };

    /**
     * 连接关闭事件
     */
    ws.onclose = function (e) {
        $(document.body).html("<h1 style='text-align: center'>连接已断开，请刷新页面重新登录。</h1>");
    };

    /**
     * 异常事件
     */
    ws.onerror = function (e) {
        $(document.body).html("<h1 style='text-align: center'>服务器[" + webim +
            "]: 拒绝了连接. 请检查服务器是否启动. </h1>");
        console.log("onerror: " + e.data);
    };
}
var down=0;   //设置全局变量down，用来记录ctrl是否被按下；
document.onkeydown = function (e) {
    e = e || window.event;
    if(e.keyCode==17){
        down=1;      //ctrl按下
    }
    if(e.keyCode==13){
        if(down==1){
            document.getElementById("msg_content").value+= '\r\n';
            down=0;     //换行后记得将全局变量置为1，否则enter将变成换行，失去发送功能
        }else{
            sendMsg($('#msg_content').val(),'text')    //执行按钮发送的功能
        }

    }

    //var ev = document.all ? window.event : e;
    //if (ev.keyCode == 13) {
    //    sendMsg($('#msg_content').val(), 'text');
    //    return false;
    //} else {
    //    return true;
    //}
};
function getActiveId() {
    var active_id = $('.active:eq(0)').attr('id');
    return active_id;
}
function getActiveFd() {
    var fd = $('.active:eq(0)').attr('fd');
    return fd;
}
function getActiveUserid() {
    var userid = $('.active:eq(0)').attr('userid');
    return userid;
}
function getAvatar(){
    var active_id = $('.active:eq(0)').attr('id');
    if(active_id=='inroom_0'){
        return avatarleadermessage;
    }else{
        var src=$('.active:eq(0) img').attr('src');;
        return src;
    }
}
function addHistoryList(meg, useridType) {
    var userid = meg[useridType];
    if (HistoryList[userid] == undefined) {
        HistoryList[userid] = new Object();
        HistoryList[userid]['history'] = new Array();
        // HistoryList[dataObj.history[i]['to_userid']]=new Object();
    }
    HistoryList[userid]['history'].push(meg);
}
var activeMessageUsers = Array();
function addSend_user_list(userid) {
    var user = new Object();
    user['userid'] = userid;
    user['fd'] = $('#inroom_' + userid).attr('fd');
    user['username'] = $('#inroom_' + userid).attr('username');
    send_user_list.push(user);
}
function seeMessage(userid) {
    $("#inroom_" + userid + "  img").attr('src', avatarworkermessage);
    $("#inroom_" + userid).addClass('not_read');
    initRemote();
}
function initRemote(){
    var notReadCount=$('.not_read').size();
    if(notReadCount>0){
        $('.top_hide .r_p_common_extent').addClass('r_p_kefu');
      // parent.onShowMsg(1);
    }else{
        $('.top_hide .r_p_common_extent').removeClass('r_p_kefu');
     //   parent.onShowMsg(0);
    }
}
function removeMessage(userid) {
    $useridSrc = $("#inroom_" + userid + " img").attr('src');
    ;
    if ($useridSrc == avatarleadermessage) {
        $("#inroom_" + userid + " img").attr('src', avatarleaderonline);
    }
    if ($useridSrc == avatarworkermessage) {
        $("#inroom_" + userid + " img").attr('src', avatarworkeronline);
    }
    if ($useridSrc == avatarofflinemessage) {
        $("#inroom_" + userid + " img").attr('src', avataroffline);
    }
    $("#inroom_" + userid).removeClass('not_read');
    initRemote();
    ws.send($.toJSON({cmd: 'updateMessage', to_userid: userid, from_userid: user.userid}));
}
function selectUser(userid) {
    send_user_list.splice(0, send_user_list.length);
    addSend_user_list(userid);
    var active_id = $('.active:eq(0)').attr('id');
    $('.active').removeClass('active');
    $('#inroom_' + userid).addClass('active');
    var last_login=$('#inroom_' + userid).attr('last_login');
    $('#last_login_time').html(last_login);
    var username=$('#inroom_' + userid).attr('username');
    $('#span_name').html(username);
    if(userid!=0){
        removeMessage(userid);
        activeMessageUsers[active_id] = $('#chat-messages').html();
        $('#chat-messages').html('');
        if (HistoryList[userid] == undefined) {
            HistoryList[userid] = new Object();
            HistoryList[userid]['history'] = new Array();
            // HistoryList[dataObj.history[i]['to_userid']]=new Object();
        } else {
            if (activeMessageUsers['inroom_' + userid] == undefined) {

            } else {
                $('#chat-messages').html(activeMessageUsers['inroom_' + userid]);
            }
            if (HistoryList[userid].hasOwnProperty('history')) {
                showHistory(HistoryList[userid]);
                HistoryList[userid]['history'] = new Array();
            } else {
                console.log('234');
            }

        }
    }else{
        showHistory(allHistoryList);
    }

    $('#chat-messages')[0].scrollTop = 1000000;


}

/**
 * 显示所有在线列表
 * @param dataObj
 */
function showOnlineList(dataObj) {

    var childrenLength=dataObj.list.length;
    if(childrenLength>=0){
        $('#children_count').html(childrenLength);
    }else{
        $('#children_count').html(0);
    }

    for (var i = 0; i < dataObj.list.length; i++) {
        $('#inroom_' + dataObj.list[i].userid).attr('fd', dataObj.list[i].fd);
        $('#inroom_' + dataObj.list[i].userid).attr('userid', dataObj.list[i].userid);
        $('#inroom_' + dataObj.list[i].userid + ' img').attr('src', avatarworkeronline);
        //$('#left-userlist').preappend($('#inroom_' + dataObj.list[i].userid));
        $('#left-userlist h2:eq(0)').after($('#inroom_' + dataObj.list[i].userid));
        userlist[dataObj.list[i].userid] = dataObj.list[i].name;
    }
    // $('#left-userlist').prepend(li);
    // $('#userlist').html(option);
    //for (var i = 0; i < dataObj.list.length; i++) {
    //    li = li + "<li id='inroom_" + dataObj.list[i].fd + "'>" +
    //    "<a href=\"javascript:selectUser('"
    //    + dataObj.list[i].fd + "')\">" + "<img src='" + dataObj.list[i].avatar
    //    + "' title='" + dataObj.list[i].name + "' width='50' height='50'></a></li>";
    //
    //    userlist[dataObj.list[i].fd] = dataObj.list[i].name;
    //
    //    if (dataObj.list[i].fd != client_id) {
    //        option = option + "<option value='" + dataObj.list[i].fd + "' id='user_" + dataObj.list[i].fd + "'>"
    //            + dataObj.list[i].name + "</option>"
    //    }
    //}
    //$('#left-userlist').html(li);
    //$('#userlist').html(option);
}
function initUsersHistory(dataObj) {
    var msg;
    if (debug) {
        console.dir(dataObj);
    }
    for (var i = 0; i < dataObj.history.length; i++) {
        if (HistoryList[dataObj.history[i]['to_userid']] == undefined) {
            HistoryList[dataObj.history[i]['to_userid']] = new Object();
        }
        if (HistoryList[dataObj.history[i]['from_userid']] == undefined) {
            HistoryList[dataObj.history[i]['from_userid']] = new Object();
        }
        if (dataObj.history[i]['to_userid'] != user.userid) {
            if (HistoryList[dataObj.history[i]['to_userid']].hasOwnProperty('history')) {
                HistoryList[dataObj.history[i]['to_userid']]['history'].push(dataObj.history[i]);
            } else {
                HistoryList[dataObj.history[i]['to_userid']]['history'] = Array();
                HistoryList[dataObj.history[i]['to_userid']]['history'].push(dataObj.history[i]);
            }

        }
        if (dataObj.history[i]['from_userid'] != user.userid) {

            if (HistoryList[dataObj.history[i]['from_userid']].hasOwnProperty('history')) {
                HistoryList[dataObj.history[i]['from_userid']]['history'].push(dataObj.history[i]);
            } else {
                HistoryList[dataObj.history[i]['from_userid']]['history'] = Array();
                HistoryList[dataObj.history[i]['from_userid']]['history'].push(dataObj.history[i]);
            }
        }
    }

}

function showLastMessage(msg){
        if (msg.time) {
                time_str = GetDateT(dataObj.time)
         }else {
                time_str = GetDateT();
        }
        var content='';
        if(msg.type='image'){
            content='图片';
        }else{
            content=msg.data;
        }
        var minicontent=content.substr(0,6);
        minicontent=minicontent+'...';
        $('#inroom_'+msg.userid+" p:eq(1)").html(minicontent);
        $('#inroom_'+msg.userid+" span:eq(0)").html(time_str);
}
/**
 * 显示所有在线列表
 * @param dataObj
 */
function showHistory(dataObj) {
    var msg;
    if (debug) {
        console.dir(dataObj);
    }
    for (var i = 0; i < dataObj.history.length; i++) {
        msg = dataObj.history[i]['msg'];
        if (!msg) continue;
        msg['time'] = dataObj.history[i]['time'];
        msg['user'] = dataObj.history[i]['user'];
        if (dataObj.history[i]['type']) {
            msg['type'] = dataObj.history[i]['type'];
        }
        if (msg['channal'] < 10) {
            msg['channal'] = 3;
        }
        msg['to_username'] = dataObj.history[i]['to_username'];
        showNewMsg(msg);
    }
}
function changeChildrenCount(count,type){
    var chilcount=$('#children_count').html();
    chilcount=parseInt(chilcount);
    if(type=='add'){
        $('#children_count').html(chilcount+count);
    }
    if(type=='reduce'){
        $('#children_count').html(chilcount-count);
    }
}
/**
 * 当有一个新用户连接上来时
 * @param dataObj
 */
function showNewUser(dataObj) {
    userlist[dataObj.userid] = dataObj.name;
    changeChildrenCount(1,'add');
    $('#inroom_' + dataObj.userid + " a").css('color', 'yellowgreen');
    $('#inroom_' + dataObj.userid).attr('fd', dataObj.fd);
    $('#left-userlist h2:eq(0)').after($('#inroom_' + dataObj.userid));
    // $('#left-userlist').prepend($('#inroom_' + dataObj.userid));
    var src = $('#inroom_' + dataObj.userid + " img:eq(0)").attr('src');
    if (src != undefined && src == avatarofflinemessage) {
        $('#inroom_' + dataObj.userid + " img:eq(0)").attr('src', avatarworkermessage);
    } else {
        $('#inroom_' + dataObj.userid + " img:eq(0)").attr('src', avatarworkeronline);
    }
    for (useri in send_user_list) {
        if (dataObj.userid == send_user_list[useri]['userid']) {
            send_user_list[useri]['fd'] = dataObj.fd;
        }
    }
    //$('#left-userlist').prepend(
    //    "<li id='inroom_" + dataObj.fd + "'>" +
    //        '<a style="color:yellowgreen;" href="javascript: selectUser(\'' + dataObj.fd + '\')">' + dataObj.name+"</a></li>");

}
var lasttime='';
/**
 * 显示新消息
 */
function showNewMsg(dataObj) {
    var content;
    var contentLeftBegin = '<div class="message_accept clear"><img class="left" src="'+dataObj.avatar+'" alt=""><p class="message_text left">';
    var contentEnd = '</p> </div>';
    var contentRightBegin='<div class="message_send clear"><img class="right" src="'+dataObj.avatar+'"  alt=""> <p class="message_text right">';
    if (!dataObj.type || dataObj.type == 'text') {
        content = xssFilter(dataObj.data);
    }
    else if (dataObj.type == 'image') {
        contentLeftBegin = '<div class="message_accept clear"><img class="left" src="'+dataObj.avatar+'" alt=""><p class="left">';
        contentRightBegin='<div class="message_send clear"><img class="right" src="'+dataObj.avatar+'"  alt=""> <p class="right">';
       // var image = eval('(' + dataObj.data + ')');
        var image = dataObj.data;
        content = '<br /><a href="' + image.url + '" target="_blank"><img src="' + image.thumb + '" /></a>';
    }

    var fromId = dataObj.from;
    var channal = dataObj.channal;

    content = parseXss(content);
    var said = '';
    var time_str;

    if (dataObj.time) {
        time_str = GetDateT(dataObj.time)
    } else {
        time_str = GetDateT();
    }
    time_str='<p class="history_time">'+time_str+'</p>';
    $("#msg-template .msg-time").html(time_str);
    if (fromId == 0) {
        $("#msg-template .userpic").html("");
        $("#msg-template .content").html(
            "<span style='color: green'>【系统消息】</span> " + content);
    }
    else {
       var allContent=contentLeftBegin;
        if(dataObj.userid==user.userid){
            allContent=contentRightBegin;
        }
        allContent+=content;
        allContent+=contentEnd;
        //var html=allContent;
        //$("#msg-template .content").html(html);
    }

    if(time_str!=lasttime){
        lasttime=time_str;
        $("#chat-messages").append(time_str);
    }

    $("#chat-messages").append(allContent);
    $('#chat-messages')[0].scrollTop = 1000000;
}

function xssFilter(val) {
    val = val.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\x22/g, '&quot;').replace(/\x27/g, '&#39;');
    return val;
}

function parseXss(val) {
    for (var i = 1; i < 20; i++) {
        val = val.replace('#' + i + '#', '<img src="/Template/default/static/img/face/' + i + '.gif" />');
    }
    val = val.replace('&amp;', '&');
    return val;
}

function GetDateT(time_stamp) {
    var d;
    d = new Date();

    if (time_stamp) {
        d.setTime(time_stamp * 1000);
    }
    var h, i, s;
    h = d.getHours();
    i = d.getMinutes();
    s = d.getSeconds();

    h = ( h < 10 ) ? '0' + h : h;
    i = ( i < 10 ) ? '0' + i : i;
    s = ( s < 10 ) ? '0' + s : s;
    return h + ":" + i + ":" + s;
}

function getRequest() {
    var url = location.search; // 获取url中"?"符后的字串
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);

        strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            var decodeParam = decodeURIComponent(strs[i]);
            var param = decodeParam.split("=");
            theRequest[param[0]] = param[1];
        }

    }
    return theRequest;
}

//function selectUser(userid) {
//    $('#userlist').val(userid);
//}

function delUser(fd, userid) {
    //$('#user_' + fd).remove();
    $('#inroom_' + userid).attr('fd', -1);
    $('#inroom_' + userid + " a").removeAttr('style');
    $("#inroom_" + userid + "  img").attr('src', avataroffline);
    var parent_id= $('#inroom_' + userid).parent().attr('id');
    if(parent_id=='left-userlist'){
        changeChildrenCount(1,'reduce');
    }
    delete (userlist[fd]);
}
//群发信息
function addMessage(msg) {
    msg.channal = 11;
    var curentMessage = JSON.parse(JSON.stringify(msg));
    var active_id = getActiveId();
    if (active_id == ("inroom_" + msg.to_userid)||active_id==("inroom_0")) {
        showNewMsg(msg);
    } else {
        var oneHistory = new Object();
        oneHistory['from_userid'] = user.userid;
        oneHistory['msg'] = curentMessage;
        oneHistory['time'] = Date.parse(new Date());
        oneHistory['to_userid'] = msg.to_userid;
        oneHistory['to_username'] = msg.to_username;
        oneHistory['type'] = msg.type;
        oneHistory['user'] = user;
        oneHistory['username'] = user.username;
        addHistoryList(oneHistory, 'to_userid');
    }
}

function sendMsg(content, type){
    var msg = {};

    if (typeof content == "string") {
        content = content.replace(" ", "&nbsp;");
    }

    if (!content) {
        return false;
    }
    var activeId=getActiveId();
    var fd=getActiveFd();
    var to_userid=getActiveUserid();
    var avatar=getAvatar();
    if (activeId == 'inroom_0') {
        msg.cmd = 'message';
        msg.from_userid=user.userid;
        msg.userid=user.userid;
        msg.from = client_id;
        msg.channal = 0;
        msg.data = content;
        msg.avatar = avatar;
        msg.users=send_user_list;
        msg.type = type;
        msg.username=user.username
        msg.to_userid=0;
        ws.send($.toJSON(msg));
    }
    else {
        msg.cmd = 'message';
        msg.from_userid=user.userid;
        msg.from = client_id;
        msg.to = fd;
        msg.userid=user.userid;
        msg.to_userid=to_userid;
        msg.channal = 1;
        msg.data = content;
        msg.username=user.username
        msg.to_username=user.username
        msg.avatar = avatar;
        msg.users=send_user_list;
        msg.type = type;
        ws.send($.toJSON(msg));
    }
    addMessage(msg);
    $('#msg_content').val('')
}
function sendMsg_qf(content, type) {
    var msg = {};

    if (typeof content == "string") {
        content = content.replace(" ", "&nbsp;");
    }

    if (!content) {
        return false;
    }
    msg.cmd = 'message';
    msg.from = client_id;
    // msg.to = $('#userlist').val();
    msg.channal = 1;
    msg.data = content;
    msg.users = send_user_list;
    msg.type = type;
    msg.userid = user.userid;
    msg.username = user.username;
    msg.avatar = avatarworkeronline;
    ws.send($.toJSON(msg));
    for (useri in send_user_list) {
        msg.to = send_user_list[useri]['userid'];
        msg.to_username = send_user_list[useri]['username'];
        addMessage(msg);
        //showNewMsg(msg);


    }

    //if ($('#userlist').val() == 0) {
    //    msg.cmd = 'message';
    //    msg.from = client_id;
    //    msg.channal = 0;
    //    msg.data = content;
    //    msg.users=send_user_list;
    //    msg.type = type;
    //    ws.send($.toJSON(msg));
    //}
    //else {
    //    msg.cmd = 'message';
    //    msg.from = client_id;
    //    msg.to = $('#userlist').val();
    //    msg.channal = 1;
    //    msg.data = content;
    //    msg.users=send_user_list;
    //    msg.type = type;
    //    ws.send($.toJSON(msg));
    //}

    $('#msg_content').val('')
}

$(document).ready(function () {
    var a = '';
    for (var i = 1; i < 20; i++) {
        a = a + '<a class="face" href="#" onclick="selectFace(' + i + ');return false;"><img src="/Template/default/static/img/face/' + i + '.gif" /></a>';
    }
    $("#show_face").html(a);
});

(function ($) {
    $.fn.extend({
        insertAtCaret: function (myValue) {
            var $t = $(this)[0];
            if (document.selection) {
                this.focus();
                sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            }
            else if ($t.selectionStart || $t.selectionStart == '0') {

                var startPos = $t.selectionStart;
                var endPos = $t.selectionEnd;
                var scrollTop = $t.scrollTop;
                $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                this.focus();
                $t.selectionStart = startPos + myValue.length;
                $t.selectionEnd = startPos + myValue.length;
                $t.scrollTop = scrollTop;
            }
            else {
                show_face
                this.value += myValue;
                this.focus();
            }
        }
    })
})(jQuery);

function selectFace(id) {
    var img = '<img src="/Template/default/static/img/face/' + id + '.gif" />';
    $("#msg_content").insertAtCaret('#' + id + '#');
    closeChatFace();
}

function showChatFace() {
    $("#chat_face").attr("class", "chat_face chat_face_hover");
    $("#show_face").attr("class", "show_face show_face_hovers");
}

function closeChatFace() {
    $("#chat_face").attr("class", "chat_face");
    $("#show_face").attr("class", "show_face");
}

function toggleFace() {
    $("#chat_face").toggleClass("chat_face_hover");
    $("#show_face").toggleClass("show_face_hovers");
}

