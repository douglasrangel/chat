(function($) {

    var Chat = {
        $sendMsgBtn : null,
        $chatMessage : null,
        $history  : null,
        $users   : null,
        timeout : 3 * 1000,

        init : function() {
            this.$sendMsgBtn  = $('.send-message');
            this.$chatMessage = $('.chat-message');
            this.$history     = $('.conversation-history');
            this.$users =       $('.users-list');

            var self = this;
            this.$sendMsgBtn.on('click', $.proxy(this.sendMessage, this) );
            this.$chatMessage.on('keypress', function(evt) {
               if ( evt.which == 13 ) {
                   self.sendMessage();
               }
            });

            setInterval(function() {
                self.updateUsers();
                self.updateChat();
            }, this.timeout);

            this.scrollToBottom();
        },

        scrollToBottom : function() {
          this.$history.scrollTop(this.$history[0].scrollHeight);
        },

        sendMessage: function() {
            var msg = this.$chatMessage.val();

            if( msg.length > 0 ) {
                var self = this;
                $.get(Ajax.url, { 'action' : 'message', 'message' : msg}, function(data) {
                    self.$chatMessage.val('');
                    self.updateChat();
                });
            }
        },

        updateChat : function() {
            var lastMsgDate = this.$history.find('> ul li:last-child').data('msg-date');

            if ( lastMsgDate === undefined ) lastMsgDate = 0;

            var self = this;
            $.getJSON(Ajax.url, {'action' : "message", 'update_messages' : 1, 'last_msg_date' : lastMsgDate}, function(newMessages) {
                if ( newMessages !== undefined && newMessages.length > 0 ) {
                    //insere as mensagens novas na tela
                    self.insertNewMessages(newMessages);
                }
            });
        },
        insertNewMessages : function(newMessages) {
            var list = '';
            $.each(newMessages, function(key, msg) {
                list += '<li class="media" data-msg-date="' + msg.date +  '"> \
                            <div class="media-body"> \
                                <div class="media"> \
                                    <a class="pull-left" href="#"> \
                                        <img class="media-object img-circle " src="' + msg.gravatar + '" /> \
                                    </a> \
                                    <div class="media-body" > \
                                        '+msg.message+'<br />\
                                        <small class="text-muted"> '+ msg.name +' | ' + moment(msg.date).format('DD/MM/YYYY HH:mm:ss') + '</small>\
                                        <hr />\
                                    </div>\
                                </div>\
                            </div>\
                        </li>';
            });

            var $list = $(list).hide();
            this.$history.find('> ul').append($list);
            $list.fadeIn('slow');

            this.scrollToBottom();

        },

        updateUsers : function() {
            var self = this;
            $.getJSON(Ajax.url, {'action' : 'user', 'update_users' : 1}, function(users) {
                if ( users !== undefined && users.length > 0 ) {
                    self.insertNewUsers(users);
                } else {
                    self.$users.html('');
                }
            });
        },
        insertNewUsers : function(users) {
            this.$users.html('');
            var list = '';
            $.each(users, function(key, user) {
                list += '<li class="media">\
                            <div class="media-body">\
                                <div class="media">\
                                    <a class="pull-left" href="#">\
                                        <img class="media-object img-circle" style="max-height:40px;" src="' + user.gravatar + '" />\
                                    </a>\
                                    <div class="media-body" >\
                                        <h5>\
                                            <a href="#" class="new-private-chat" data-hash="'+ user.user_hash+'">\
                                            '+user.name+'\
                                            </a>\
                                        </h5>\
                                        <small class="text-muted">\
                                        Entrou em\
                                        '+ moment(user.entered_at).format('DD/MM/YYYY HH:mm:ss') +'\
                                        </small>\
                                    </div>\
                                </div>\
                            </div>\
                        </li>';
            });
            var $list = $(list);
            this.$users.append($list);
        }
    }

    $(document).ready(function() {
        Chat.init();
    });

})(jQuery);


