!function($, w, d, undefined) {
    'use strict';
    var
        ERROR_CLASS = 'danger',
        WARNING_CLASS = 'warning',
        SUCCESS_CLASS = 'success',
        notice_counter = 0,

        publisher,
        session,

        o = {
            apiKey: "",
            token: "",
            sessionId: "",
            debug: false,
            name: 'Publisher',
            showPublisher: true,
            publisher: {
                width: 320,
                height: 240
            },
            subscriber: {
                width: 320,
                height: 240
            },
            lang: {
                unpublish_stream: "{name} finished stream publeshing",
                publish_stream: "{name} started stream publeshing",
                connection_destroyed: "{name} was disconnected",
                is_waiting: "{name} is waiting you",
                disconnect: "{name} was disconnected",
                disconnected_by_yourself: "You have been disconnected from the session",
                start_stream_publeshing: "You started video publishing",
                finish_stream_publeshing: "You finished video publishing",
                has_not_requirements: "Your browser doesn't support video streaming",
                error_connection: "There was a connection error. Trying again",
                restricted_camera_permissions: "You has restricted video and audio permissions"
            },
            autoResize: true,
            noticeTmpl: function(notice) {}
        };

    var exceptionHandler = {
        handlers: {
            //"code === 1013" : "peer2peer",
            //"code === 1004" : "expire",
            "code === 1006 || code === 1008 || code === 1014" : "reconnect",
            "code === 1500" : "restrictedByUser"
        },

        reconnect: function(event) {
            raiseNotification(t("error_connection"), ERROR_CLASS);
        },

        restrictedByUser: function(event) {
            raiseNotification(t("restricted_camera_permissions"), ERROR_CLASS);
            unpublishVideo();
        },

        defaultHandler: function(event) {
            raiseNotification(event.message, ERROR_CLASS);
        },

        route: function(event) {
            var
                handlers = exceptionHandler.handlers,
                condition,
                code = event.code;

            for(var i in handlers) {
                condition = eval(i);
                if(condition === true) {
                    exceptionHandler[handlers[i]](event);
                    return true;
                }
            }
            exceptionHandler.defaultHandler(event);
        }
    };

    function modifiyNoticeHeight() {
        if($('#notification').height() >= o.maxNotificationHeight) {
            $('#notification').css("overflow-y", "scroll");
        }
    }

    function raiseNotification(msg, type) {
        var
            delayMS = 169000,
            fadeOutMS = 500;
        $('#notification').prepend(o.noticeTmpl.call(this, notice_counter, type, msg));
        modifiyNoticeHeight();
        $('#notice-'+ notice_counter).show().delay(delayMS).fadeOut(fadeOutMS, function(){
            $(this).remove();
            modifiyNoticeHeight();
        });
        notice_counter++;
    }

    function publishVideo() {
        if($.trim($("#publisher").html()).length > 0) {
            $('#unpublishVideo').show();
            $('#publishVideo').hide();
            return false;
        }

        if(!publisher) {
            var
                publisherID = 'publisher',
                publisherProps = {
                    width: o.publisher.width,
                    height: o.publisher.height,
                    name: o.name,
                    insertMode: 'append'
                };

            $('#publishVideo').hide();

            publisher = OT.initPublisher(publisherID, publisherProps, function(error) {
                // All exceptions are caught by exceptionHandler.route handler
                if(error) {
                    return false;
                }
                $('#messages_area').css('height', '');
                session.publish(publisher);
            });


            publisher.on({
                streamCreated: function (event) {
                    $('#unpublishVideo').show();
                    if(o.showPublisher == false) {
                        $('#publisher').hide();
                    }
                    raiseNotification(t("start_stream_publeshing"), SUCCESS_CLASS);
                },
                streamDestroyed: function (event) {
                    $('#messages_area').css('height', '100%');
                    raiseNotification(t("finish_stream_publeshing"), WARNING_CLASS);
                    $('#unpublishVideo').hide();
                    $('#publishVideo').show();
                }
            });
        }
    }

    function disconnect() {
        session.disconnect();
    }

    function unpublishVideo() {
        if (publisher) {
            session.unpublish(publisher);
            publisher.destroy();
            publisher = null;
        }
    }

    function bootstrap() {
        // Automatically resize video containers
        if(o.autoResize) {
            calculateBlocksSize();
        }

        addInterfaceEventListeners();

        session = OT.initSession(o.apiKey, o.sessionId);

        session.on("streamCreated", function(event) {
            var stream = event.stream,
                subscriberID = "subscriber",
                subscriberProperties = {
                    width: o.subscriber.width,
                    height: o.subscriber.height,
                    insertMode: "append"
                };
            var subscriber = session.subscribe(stream, subscriberID, subscriberProperties, function (error) {
                if(error){
                    return false;
                }
                raiseNotification(t("publish_stream", {"{name}":stream.name}), SUCCESS_CLASS);
            });
        });
        session.on("sessionDisconnected", function(event) {
            window.open('','_parent','');
            window.close();
        });
        session.on("streamDestroyed", function(event) {
            raiseNotification(t("unpublish_stream", {"{name}":event.stream.name}), WARNING_CLASS);
        });

        session.connect(o.token, function (error) {
            if(error) {
                raiseNotification("Unable to connect: " + error.message, ERROR_CLASS);
            }
            if(session.capabilities.publish == 1) {
                publishVideo();
            } else {
                raiseNotification("You cannot publish an audio-video stream.", ERROR_CLASS);
            }
        });
    }

    function addInterfaceEventListeners() {
        $('#publishVideo').on({ click: publishVideo });
        $('#unpublishVideo').on({ click: unpublishVideo });
        $('#disconnectLink').on({ click: disconnect });
    }

    $.jOpenTok = function(options) {
        o = $.extend(true, o, options);
        OT.on("exception", exceptionHandler.route);
        o.debug == true ? OT.setLogLevel(OT.DEBUG) : OT.setLogLevel(OT.NONE);
        if (OT.checkSystemRequirements() == 1) {
            bootstrap();
        } else {
            raiseNotification(t("has_not_requirements"), ERROR_CLASS);
        }
    }

    function t(key, replacement) {
        var phrase = o.lang[key];
        if(typeof(phrase) == "undefined")
            return key;
        if(typeof(replacement) !== "object")
            return phrase;
        for (var i in replacement) {
            phrase = phrase.replace(i, replacement[i]);
        }
        return phrase;
    }

    function calculateBlocksSize() {
        var pubWidth = Math.round(parseInt($('#publisher').width()));
        var pubHeight = Math.round(pubWidth - (pubWidth * 0.25));
        var subWidth = Math.round(parseInt($('#subscriber').width()));
        var subHeight = Math.round(subWidth - (subWidth * 0.25));

        $('#subscriber').css('height', subHeight);
        $('#publisher').css('height', pubHeight);

        o.publisher.width = pubWidth;
        o.publisher.height = pubHeight;
        o.subscriber.width = subWidth;
        o.subscriber.height = subHeight;
    }

}(jQuery, window, document);
