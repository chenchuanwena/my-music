var ws = {};
var client_id = 0;
var userlist = {};
var GET = getRequest();
var face_count = 19;
var send_user_list=Array();
var allHistoryList=Array();
var HistoryList=Array();


$(document).ready(function () {
    var avatar=avataroffline;
    if(user.parent.nickname==null){
        $('.nav-header').html('我是总代');

    }else{

        if(user.parent.not_read_number>0){
            avatar=avatarofflinemessage;
        }
        $('.nav-header').html("<a style='display:block;height:30px;width:200px;position: relative; ' href=\"javascript:selectUser('"
            + user.parent.userid + "')\">"+"<img src='" + avatar+"' width='30' height='30'/>我的上级</a>");
        $('.nav-header').attr('id','inroom_'+user.parent.userid);
        $('.nav-header').attr('fd',-1);
        $('.nav-header').attr('username',user.parent.username);
        userlist[user.parent.userid]=user.parent.username;
        var divItem='<div class="divItem"> <input class="classCheckbox" name="itemChecks" type="checkbox" value="'+user.parent.userid+'" >我的上级 </div>';
        $('#divContent').append(divItem);
    }
    var li='';
    for (var i = 0; i < user.childrens.results.length; i++) {
        if(user.childrens.results[i].not_read_number>0){
            avatar=avatarofflinemessage;
        }else{
            avatar=avataroffline;
        }
        li = "<li id='inroom_" + user.childrens.results[i].userid + "' fd='-1' username='"+user.childrens.results[i].username+"'>" +
            "<a style='display:block;height:30px;width:200px; position: relative;' href=\"javascript:selectUser('"
            + user.childrens.results[i].userid + "')\"><img src='" + avatar
            + "' title='" + user.childrens.results[i].username + "' width='30' height='30'>" + user.childrens.results[i].username+"</a></li>";
        if(avatar==avatarofflinemessage){
            $('#left-userlist').prepend(li);
        }else{
            $('#left-userlist').append(li);
        }
           userlist[user.childrens.results[i].userid]=user.childrens.results[i].username;
        var divItem='<div class="divItem"> <input class="classCheckbox"  name="itemChecks" type="checkbox" value="'+user.childrens.results[i].userid+'" >'+user.childrens.results[i].username+' </div>';
        $('#divContent').append(divItem);
    }

    //使用原生WebSocket
    if (window.WebSocket || window.MozWebSocket)
    {
        ws = new WebSocket(webim.server);
    }
    //使用flash websocket
    else if (webim.flash_websocket)
    {
        WEB_SOCKET_SWF_LOCATION = "/static/flash-websocket/WebSocketMain.swf";
        $.getScript("/static/flash-websocket/swfobject.js", function () {
            $.getScript("/static/flash-websocket/web_socket.js", function () {
                ws = new WebSocket(webim.server);
            });
        });
    }
    //使用http xhr长轮循
    else
    {
        ws = new Comet(webim.server);
    }
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
        msg.userid=user.userid;
        msg.avatar = user.avatar;
        msg.parent=user.parent;
        msg.childrens=user.childrens;
        ws.send($.toJSON(msg));
    };

    //有消息到来时触发
    ws.onmessage = function (e) {
        var message = $.evalJSON(e.data);
        var cmd = message.cmd;
        if (cmd == 'login')
        {
            client_id = $.evalJSON(e.data).fd;
           // ws.send($.toJSON({cmd : 'getUnreadUser',userid:user.userid,parentid:msg.parent.userid}));
            //获取在线列表
            ws.send($.toJSON({cmd : 'getOnline',userid:user.userid,parentid:msg.parent.userid}));
            //获取历史记录
            ws.send($.toJSON({cmd : 'getHistory',userid:user.userid}));
            //alert( "收到消息了:"+e.data );
        }
        else if (cmd == 'getOnline')
        {
            showOnlineList(message);
        }
        else if (cmd == 'getHistory')
        {
            //showHistory(message);
            allHistoryList=message;
            initUsersHistory(message);

            if(user.parent.nickname==null){
                HistoryList[0]=new Object();
                HistoryList[0]['history']=new Array();
                var userid=$('#left-userlist li:eq(0)').attr('userid');
                selectUser(userid);

            }else{
                if(HistoryList[user.parent.userid]==undefined){
                    HistoryList[0]=new Object();
                    HistoryList[0]['history']=new Array();

                }else{
                    showHistory(HistoryList[user.parent.userid]);
                    selectUser(user.parent.userid);
                }
            }

        }
        else if (cmd == 'newUser')
        {
            showNewUser(message);
           // showNewMsg(message);
        }
        else if (cmd == 'fromMsg')
        {
            $active_id=getActiveId();
            if($active_id==("inroom_"+message.from)){
                showNewMsg(message);
            }else{
                var oneHistory=new Object();
                oneHistory['from_userid']=message['from'];
                message['channal']= 10+message['channal'];
                oneHistory['msg']=message;
                oneHistory['to_userid']=user.userid;
                oneHistory['to_username']=user.username;
                oneHistory['type']=message['type'];
                oneHistory['user']=user;
                oneHistory['username']=message['username'];
                seeMessage(message['from']);

                addHistoryList(oneHistory,'from_userid');
            }

        }
        else if (cmd == 'offline')
        {
            var cid = message.fd;
            var userid=message.userid;
            delUser(cid,userid);
            //showNewMsg(message);
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
        $(document.body).html("<h1 style='text-align: center'>服务器[" + webim.server +
            "]: 拒绝了连接. 请检查服务器是否启动. </h1>");
        console.log("onerror: " + e.data);
    };
}

document.onkeydown = function (e) {
    var ev = document.all ? window.event : e;
    if (ev.keyCode == 13) {
        sendMsg($('#msg_content').val(), 'text');
        return false;
    } else {
        return true;
    }
};
function getActiveId(){
    var active_id= $('.active:eq(0)').attr('id');
    return active_id;
}
function addHistoryList(meg,useridType){
    var userid=meg[useridType];
    if(HistoryList[userid]==undefined){
        HistoryList[userid]=new Object();
        HistoryList[userid]['history']=new Array();
        // HistoryList[dataObj.history[i]['to_userid']]=new Object();
    }
    HistoryList[userid]['history'].push(meg);
}
var activeMessageUsers=Array();
function addSend_user_list(userid){
    var user=new Object();
    user['userid']=userid;
    user['fd']=$('#inroom_'+userid).attr('fd');
    user['username']=$('#inroom_'+userid).attr('username');
    send_user_list.push(user);
}
function seeMessage(userid){
    if(user.parent.userid==userid){
        $("#inroom_"+userid+" a img").attr('src',avatarleadermessage);
    }else{
        $("#inroom_"+userid+" a img").attr('src',avatarworkermessage);
    }
}
function removeMessage(userid){
    $useridSrc=$("#inroom_"+userid+" a img").attr('src');;
    if($useridSrc==avatarleadermessage){
        $("#inroom_"+userid+" a img").attr('src',avatarleaderonline);
    }
    if($useridSrc==avatarworkermessage){
        $("#inroom_"+userid+" a img").attr('src',avatarworkeronline);
    }
    if($useridSrc==avatarofflinemessage){
        $("#inroom_"+userid+" a img").attr('src',avataroffline);
    }
    ws.send($.toJSON({cmd : 'updateMessage',to_userid:userid,from_userid:user.userid}));
}
function selectUser(userid) {
    send_user_list.splice(0,send_user_list.length);
    addSend_user_list(userid);
    var active_id= $('.active:eq(0)').attr('id');
    $('.active').removeClass('active');
    $('#inroom_'+userid).addClass('active');
    removeMessage(userid);
    activeMessageUsers[active_id]= $('#chat-messages').html();
    $('#chat-messages').html('');
    if(HistoryList[userid]==undefined){
        HistoryList[userid]=new Object();
        HistoryList[userid]['history']=new Array();
        // HistoryList[dataObj.history[i]['to_userid']]=new Object();
    }else{
        if(activeMessageUsers['inroom_'+userid]==undefined){

        }else{
            $('#chat-messages').html(activeMessageUsers['inroom_'+userid]);
        }
        if(HistoryList[userid].hasOwnProperty('history')){
            showHistory(HistoryList[userid]);
            HistoryList[userid]['history']=new Array();
        }else{
            console.log('234');
        }

    }
    $('#chat-messages')[0].scrollTop = 1000000;



}

/**
 * 显示所有在线列表
 * @param dataObj
 */
function showOnlineList(dataObj) {
    var li = '';
    //var option = "<option value='0' id='user_all' >所有人</option>";
    for (var i = 0; i < dataObj.list.length; i++) {
        if(dataObj.list[i].is_child==1){
            $('#inroom_'+dataObj.list[i].userid+" a").css('color','yellowGreen');
            $('#inroom_'+dataObj.list[i].userid).attr('fd',dataObj.list[i].fd);
            $('#inroom_'+dataObj.list[i].userid).attr('userid',dataObj.list[i].userid);
            $('#inroom_'+dataObj.list[i].userid+' img').attr('src',avatarworkeronline);
            $('#left-userlist').prepend($('#inroom_'+dataObj.list[i].userid));
            userlist[dataObj.list[i].userid] = dataObj.list[i].name;
            //li = li + "<li id='inroom_" + dataObj.list[i].fd + "'>" +
            //    "<a style='color:yellowGreen;' href=\"javascript:selectUser('"
            //    + dataObj.list[i].fd + "')\">" +  dataObj.list[i].name + "</a></li>";
            //userlist[dataObj.list[i].fd] = dataObj.list[i].name;

            //if (dataObj.list[i].fd != client_id) {
            //    option = option + "<option value='" + dataObj.list[i].fd + "' id='user_" + dataObj.list[i].fd + "'>"
            //        + dataObj.list[i].name + "</option>"
            //}


        }else{
            $('#inroom_'+dataObj.list[i].userid+" a").css('color','yellowGreen');
            $('#inroom_'+dataObj.list[i].userid).attr('fd',dataObj.list[i].fd);
            $('#inroom_'+dataObj.list[i].userid+' img').attr('src',avatarleaderonline);
            userlist[dataObj.list[i].userid] = dataObj.list[i].name;
        }


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
function initUsersHistory(dataObj){
    var msg;
    if (debug) {
        console.dir(dataObj);
    }
    for (var i = 0; i < dataObj.history.length; i++) {
        if(HistoryList[dataObj.history[i]['to_userid']]==undefined){
            HistoryList[dataObj.history[i]['to_userid']]=new Object();
        }
        if(HistoryList[dataObj.history[i]['from_userid']]==undefined){
            HistoryList[dataObj.history[i]['from_userid']]=new Object();
        }
        if(dataObj.history[i]['to_userid']!=user.userid){
            if( HistoryList[dataObj.history[i]['to_userid']].hasOwnProperty('history')){
                HistoryList[dataObj.history[i]['to_userid']]['history'].push(dataObj.history[i]);
            }else{
                HistoryList[dataObj.history[i]['to_userid']]['history']=Array();
                HistoryList[dataObj.history[i]['to_userid']]['history'].push(dataObj.history[i]);
            }

        }
        if(dataObj.history[i]['from_userid']!=user.userid){

            if( HistoryList[dataObj.history[i]['from_userid']].hasOwnProperty('history')){
                HistoryList[dataObj.history[i]['from_userid']]['history'].push(dataObj.history[i]);
            }else{
                HistoryList[dataObj.history[i]['from_userid']]['history']=Array();
                HistoryList[dataObj.history[i]['from_userid']]['history'].push(dataObj.history[i]);
            }
        }
    }
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
       // msg['time'] = dataObj.history[i]['time'];
        msg['user'] = dataObj.history[i]['user'];
        if (dataObj.history[i]['type'])
        {
            msg['type'] = dataObj.history[i]['type'];
        }
        if(msg['channal']<10){
            msg['channal'] = 3;
        }
        msg['to_username']=dataObj.history[i]['to_username'];
        showNewMsg(msg);
    }
}

/**
 * 当有一个新用户连接上来时
 * @param dataObj
 */
function showNewUser(dataObj) {
        userlist[dataObj.userid] = dataObj.name;
        if (dataObj.fd != client_id) {
            if(user.parent.userid==dataObj.userid){
                $('#inroom_'+dataObj.userid+" a").css('color','yellowgreen');
                $('#inroom_'+dataObj.userid).attr('fd',dataObj.fd);
                $('#inroom_'+dataObj.userid+" a img").attr('src',avatarleaderonline);
            }else{
                $('#inroom_'+dataObj.userid+" a").css('color','yellowgreen');
                $('#inroom_'+dataObj.userid).attr('fd',dataObj.fd);
                $('#left-userlist').prepend($('#inroom_'+dataObj.userid));
                $('#inroom_'+dataObj.userid+" img").attr('src',avatarworkeronline);
            }
            for(useri in send_user_list ){
                if(dataObj.userid==send_user_list[useri]['userid']){
                    send_user_list[useri]['fd']=dataObj.fd;
                }
            }
        }

        //$('#left-userlist').prepend(
        //    "<li id='inroom_" + dataObj.fd + "'>" +
        //        '<a style="color:yellowgreen;" href="javascript: selectUser(\'' + dataObj.fd + '\')">' + dataObj.name+"</a></li>");

}

/**
 * 显示新消息
 */
function showNewMsg(dataObj) {

    var content;
    if (!dataObj.type || dataObj.type == 'text') {
        content = xssFilter(dataObj.data);
    }
    else if (dataObj.type == 'image') {
        var image = eval('(' + dataObj.data + ')');
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
        time_str = GetDateT()
    }

    $("#msg-template .msg-time").html(time_str);
    if (fromId == 0) {
        $("#msg-template .userpic").html("");
        $("#msg-template .content").html(
            "<span style='color: green'>【系统消息】</span> " + content);
    }
    else {
        var html = '';
        var to = dataObj.to;

        //历史记录
        if (channal == 3)
        {
            said = '对'+dataObj.to_username+'说: ';
            html += '<span style="color: green">【历史记录】</span><span style="color: orange">' + dataObj.user.name + said;
            html += '</span>';
        }
        //如果说话的是我自己
        else {
            if (client_id == fromId) {
                if (channal == 0 ||channal==10) {
                    said = '我对大家说:';
                }
                else if (channal == 1 || channal==11) {
                    said = "我对" + userlist[to] + "说:";
                }
                html += '<span style="color: orange">' + said + ' </span> ';
            }
            else {
                if (channal == 0 ||channal==10) {
                    said = '对大家说:';
                }
                else if (channal == 1 || channal==11) {
                    said = "对我说:";
                }

                html += '<span style="color: orange"><a href="javascript:selectUser('
                    + fromId + ')">' + userlist[fromId] + said;
                html += '</a></span> '
            }
        }
        html += content + '</span>';
        $("#msg-template .content").html(html);
    }
    $("#chat-messages").append($("#msg-template").html());
    $('#chat-messages')[0].scrollTop = 1000000;
}

function xssFilter(val) {
    val = val.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\x22/g, '&quot;').replace(/\x27/g, '&#39;');
    return val;
}

function parseXss(val) {
    for (var i = 1; i < 20; i++) {
        val = val.replace('#' + i + '#', '<img src="/static/img/face/' + i + '.gif" />');
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

function delUser(fd,userid) {
    //$('#user_' + fd).remove();
    $('#inroom_' + userid).attr('fd',-1);
    $('#inroom_' + userid+" a").removeAttr('style');
    $("#inroom_"+userid+" a img").attr('src',avataroffline);
    delete (userlist[fd]);
}
//群发信息
function addMessage(msg){
    msg.channal=11;
    var curentMessage=JSON.parse(JSON.stringify(msg));
    var active_id=getActiveId();
    if(active_id==("inroom_"+ msg.to)){
        showNewMsg(msg);
    }else{
        var oneHistory=new Object();
        oneHistory['from_userid']=user.userid;
        oneHistory['msg']=curentMessage;
        oneHistory['time']=Date.parse( new Date());;
        oneHistory['to_userid']=msg.to;
        oneHistory['to_username']=msg.to_username;
        oneHistory['type']=msg.type;
        oneHistory['user']=user;
        oneHistory['username']=user.username;
        addHistoryList(oneHistory,'to_userid');
    }
}
function sendMsg(content, type) {
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
    msg.users=send_user_list;
    msg.type = type;
    msg.userid=user.userid;
    msg.username=user.username;
    msg.avatar=user.avatar;
    ws.send($.toJSON(msg));
    for(useri in send_user_list ){
        msg.to=send_user_list[useri]['userid'];
        msg.to_username=send_user_list[useri]['username'];
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
        a = a + '<a class="face" href="#" onclick="selectFace(' + i + ');return false;"><img src="/static/img/face/' + i + '.gif" /></a>';
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

                this.value += myValue;
                this.focus();
            }
        }
    })
})(jQuery);

function selectFace(id) {
    var img = '<img src="/static/img/face/' + id + '.gif" />';
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

