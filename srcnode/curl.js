// libray
(function ($) {
    $.fn.getNumbByPx = function (css) {
        var Css = $(this).css(css);
        var length = Css.length;
        var numb = Number(Css.slice(0, (length - 2)));
        return numb;
    },

        $.fn.disable = function () {
            return this.each(function () {
                if ($(this).hasClass("active")) {
                    $(this).removeClass("active")
                }
                $(this).addClass("disable");
            });
        },

        $.fn.active = function (s) {
            return this.each(function () {
                if ($(this).hasClass("disable")) {
                    $(this).removeClass("disable");
                }
                if (s) {
                    $(this).addClass("active");
                } else {
                    $(this).removeClass("active");
                }
            });
        },

        $.fn.scale = function (x) {
            if (!$(this).filter(':visible').length && x != 1) return $(this);
            if (!$(this).parent().hasClass('scaleContainer')) {
                $(this).wrap($('<div class="scaleContainer">').css('position', 'relative'));
                $(this).data({
                    'originalWidth': $(window).width(),
                    'originalHeight': $(window).height()
                });
            }
            $(this).css({
                'transform': 'scale(' + x + ')',
                '-ms-transform': 'scale(' + x + ')',
                '-moz-transform': 'scale(' + x + ')',
                '-webkit-transform': 'scale(' + x + ')',
                'transform-origin': '0 0',
                '-ms-transform-origin': '0 0',
                '-moz-transform-origin': '0 0',
                '-webkit-transform-origin': '0 0',
            });
            if (x == 1) {
                $(this).unwrap().css('position', 'static');
            }
            else {
                $(this).parent()
                    .width($(this).data('originalWidth'))
                    .height($(this).data('originalHeight'));
            }
            return $(this);
        };
}(jQuery));

var Speech = (function (speech) {
    speech.recognition;
    speech.text;
    speech.init = function () {
        try {
            var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            speech.recognition = new SpeechRecognition();
            speech.recognition.continuous = true;
            speech.recognition.interimResults = true;
        }
        catch (e) {
            alert("Trình duyệt của bạn không hỗ trợ tính năng này. Hãy cài trình duyệt Chorme của Google để sử dụng tính năng luyện giao tiếp i-speak trên Educa.vn.");
        }
    };

    speech.Event = {
        start: function () {
            speech.recognition.lang = "en-AU";
            speech.recognition && speech.recognition.start();
        },
        stop: function () {
            speech.recognition && speech.recognition.stop();
        }
    };

    speech.Listen = {
        start: function (callback) {
            speech.recognition.onstart = callback;
        },
        stop: function (callback) {
            speech.recognition.onspeechend = callback;
        },
        error: function (callback) {
            speech.recognition.onerror = callback;
        },
        result: function (callback) {
            speech.recognition.onresult = function (event) {
                speech.text = "";
                for (var i = 0; i < event.results.length; i++) {
                    if (!event.results[i].isFinal) {
                        speech.text += event.results[i][0].transcript;
                    }
                }
                callback(speech.text)
            };
        }
    };
    return speech;
}(Speech || {}));

var App = (function (app) {

    /**
     * Init Constants Object;
     */

    app.Constants = {

    };

    /**
     * Consntant link get Data Respone
     */

    app.Constants.linkData0 = './js/data.json';
    app.Constants.linkData = "https://educa.vn/service/exerciseinfo";
    app.Constants.upPoint = "https://educa.vn/service/uppoint";

    return app;

}(App || {}));
/* start doNV*/
var listR = [];
var listW = [];
var N = 0;

var App = (function (app) {
    /**
     * init variables
     */
    app.Variables = {};
    /**
     * API của game
     */
    app.Variables.data = null;
    /**
     * init setinterval
     */
    app.Variables.setInterval = {
        event_listen: "",
        timer: "",
        load_percent: "",
        time: 1
    };
    /**
     * init width of screen
     */
    app.Variables.screen = 996;
    /**
     * init thanh progress bar
     */
    app.Variables.load = {};
    app.Variables.load.animationstart = $("#load");
    app.Variables.load.process = $("#process");
    app.Variables.load.span = app.Variables.load.animationstart.children("span");

    /**
     * các ngữ cảnh
     */
    app.Variables.letsplay = $("#letsplay");
    app.Variables.playing = $("#playing");
    app.Variables.win = $("#win");
    app.Variables.lose = $("#lose");
    app.Variables.end = $("#endgame");
    app.Variables.danhgia = $('#evaluate');
    /**
     * các btn
     */
    app.Variables.btn_play = $(".btn-play");
    app.Variables.btn_submit = $(".btn-submit");
    app.Variables.btn_replay = $(".btn-replay");
    app.Variables.btn_next = $(".btn-next");
    app.Variables.btn_finish = $('.btn-finish');

    app.Variables.btn_howto = $(".btn-howto");
    app.Variables.howto = $('#howto');
    app.Variables.btn_back = $('.btn-back');
    app.Variables.btn_danhgia = $('.btn-danhgia');
    app.Variables.btn_listen = $('.btn-listen');

    return app
}(App || {}));

var App = (function (app) {

    // Create Log Module
    app.Log = {
        enable: false
    };

    //Check if Log is Enabled
    app.Log.isEnabled = function () {
        return this.enable;
    };

    //Write log to console
    app.Log.write = function (content, prefix) {
        var _prefix = prefix || "Default Logging";
        if (this.enable) {
            utils.writeLog("---BEGIN " + _prefix + "---");
            utils.writeLog(content);
            utils.writeLog("---END " + _prefix + "---");
        }
    };

    //Write log string in one line
    app.Log.writeString = function (content, prefix) {
        var _prefix = prefix || "Log Tag: ";
        if (this.enable) {
            utils.writeLog(_prefix + " : " + content);
        }
    };

    //Write method log
    app.Log.writeMethod = function (name) {
        if (this.enable) {
            utils.writeLog("[ Method Logging ] ---" + name + "()");
        }
    }

    //Method aliases
    app.Log = {
        w: app.Log.write,
        ws: app.Log.writeString,
        m: app.Log.writeMethod
    };

    return app;

}(App || {}));
var App = (function (app) {

    //init HTTP object
    app.Http = {};

    app.Http.postHeaders = function (targetUrl, requestData, successCallback, failureCallback) {
        $.ajax({
            url: targetUrl,
            headers: requestData,
            type: "POST",
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: successCallback,
            failure: failureCallback,
            async: false
        });
    };

    //Send Post
    app.Http.post = function (targetUrl, requestData, successCallback, failureCallback) {
        $.ajax({
            url: targetUrl,
            data: requestData,
            type: "POST",
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: successCallback,
            failure: failureCallback
        });
    };

    //GET
    app.Http.get = function (targetUrl, successCallback, failureCallback) {
        $.ajax({
            url: targetUrl,
            type: "GET",
            success: successCallback,
            failure: failureCallback
        });
    };

    return app;

}(App || {}));
function isEmptyObject(obj) {
    return !Object.keys(obj).length;
}
function markstring(input, source) {
    if (source == undefined || isEmptyObject(source)) {
        return { message: 'Input source error!', resultCode: 0 };
    }
    if (input == undefined) {
        return { message: 'Input error!', resultCode: 0 };
    }
    var arrInput = input.toLowerCase().replace(new RegExp('[^a-z0-9 ]', 'g'), '').split(" ");
    var arrSource = source.toLowerCase().replace(new RegExp('[^a-z0-9 ]', 'g'), '').split(" ");
    var array = [];
    var index = 0;
    var counterSucc = 0;
    for (var i = 0; i < arrSource.length; i++) {
        var point = 0;
        for (var j = index; j < arrInput.length; j++) {
            if (arrInput[j] == arrSource[i]) {
                index = j;
                point = 1;
                counterSucc++;
                break;
            }
        }
        array.push(point);
    }
    var point = Math.floor(counterSucc * 100 / arrSource.length);
    var result = new Map();
    result.set("ResultCode", "1");
    result.set("Sentence", source);
    result.set("points", array);
    return '{ "mark":[' + array.toString() + '],' + '"point": ' + point + '}';
}
var App = (function (app) {
    /**
     * Init Services Object;
     */

    app.Services = {

    };

    app.Services.Educa = {

    };


    app.Services.Educa.markstring = markstring;

    /**
     * Get Number random between 0 -> max
     */

    app.Services.getNumberRandomBetween = function (max) {
        return Math.floor(Math.random() * max) + min;
    };

    /**
     * Get Number random between min -> max
     */

    app.Services.getNumberRandomBetween2 = function (max, min) {
        min = min || 0;
        return Math.floor(Math.random() * (max - min + 1) + min);
    };

    /**
     * Get Data Res
     */

    // hàm mới này phục vụ cho API.
    app.Services.getParameterByName = function (name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    // hàm mới này phục vụ cho API.
    app.Services.GetObjectFromUrl = function () {
        var currentURL = document.URL;
        var urlIdExercise = App.Services.getParameterByName("exercise_id", currentURL);
        var urlToken = App.Services.getParameterByName("token", currentURL);
        var urlusername = App.Services.getParameterByName("username", currentURL);

        return { username: urlusername, token: urlToken, exerciseid: urlIdExercise };
    }

    app.Services.getDataRes = function (callback, callerr) {
        if (App.jsonKH) {
            var dataObj = app.Services.GetObjectFromUrl();
            App.Http.postHeaders(app.Constants.linkData, dataObj,
                function (data) {
                    App.Log.w(data, 'DATA');
                    if (typeof data === "object") {
                        callback(data);
                    } else {
                        callback(JSON.parse(data));
                    }
                },
                function (err) {
                    App.Log.w(err, 'ERR');
                    callerr(err);
                }
            );
        } else {
            App.Http.get(app.Constants.linkData0,
                function (data) {
                    App.Log.w(data, 'DATA');
                    if (typeof data === "object") {
                        callback(data);
                    } else {
                        callback(JSON.parse(data));
                    }
                },
                function (err) {
                    App.Log.w(err, 'ERR');
                    callerr(err);
                }
            );
        }
    };
    /**
     * Update Question of game
     */


    app.Services.setAnswer = function (i, a) {
        if (i == 0) {
            i = 3;
        }
        $("#Answer" + i + " img").attr("src", a.I);
        $("#Answer" + i + ' p').text(a.T);
        $("#Answer" + i).removeClass("bg--true");

        if (a.R) {
            $("#Answer" + i).addClass("run");
            $("#Answer" + i).removeClass("btn-fail");
        } else {
            $("#Answer" + i).removeClass("run");
            $("#Answer" + i).addClass("btn-fail");
        }
    };


    app.Services.registerSound = function (audio, alias) {
        createjs.Sound.registerSound(audio, alias);
    };

    app.Services.registerAllSound = function (data, callback) {

        data.data.game_background_music.forEach(function (item) {
            app.Services.registerSound(item.voice, 'background_music' + item.id);
        });

        data.data.game_bot1_infore.forEach(function (item) {
            if (item.voice != "") {
                app.Services.registerSound(item.voice, 'audio_ans' + item.id);
            }
        });

        data.data.game_bot2_infore.forEach(function (item) {
            if (item.voice != "") {
                app.Services.registerSound(item.voice, 'audio_ans' + item.id);
            }
        });

        data.data.game_image_win.forEach(function (item) {
            app.Services.registerSound(item.voice, 'audio_win' + item.id);
        });

        callback ? callback() : "";
    };
    // play audio width name
    app.Services.playSound = function (name, callback) {
        var sound = createjs.Sound.play(name);
        sound.volume = 1;
        callback ? callback(sound) : "";
    };

    // splice list item and set answer...
    app.Services.spliceArr = function (m, arr) {
        var item = app.Services.getNumberRandomBetween(arr.length - 1);
        this.setAnswer(m, arr[item]);
        arr.splice(item, 1);
    };
    // isMobile
    app.Services.isMobile = function () {
        if (width_window <= 1024) {
            return true;
        }
        return false;
    };

    // //Get Question
    // app.Services.getQuestion = function (id) {
    //   $('.answer-default p').text(App.Variables.data.data.game_question[id].value);
    //   $('.mic img').attr("src", App.Variables.data.data.game_micro[0].images);
    // };

    //endGame
    app.Services.endGamePoint = function (pointSum) {
        var countPoint = Math.floor(pointSum / 10);
        if (pointSum <= 59) {
            $('.image_win img').attr("src", App.Variables.data.data.game_image_win[0].images);
        } else if (pointSum <= 89) {
            if (countPoint == 5) {
                countPoint = countPoint + 1;
            }
            $('.image_win img').attr("src", App.Variables.data.data.game_image_win[1].images);
        } else {
            if (countPoint == 8) {
                countPoint = countPoint + 1;
            }
            $('.image_win img').attr("src", App.Variables.data.data.game_image_win[2].images);
        }
    };

    app.Services.endGameVolumn = function (pointSum) {
        var countPoint = Math.floor(pointSum / 10);
        if (pointSum <= 59) {
            App.Services.playSound('audio_win' + App.Variables.data.data.game_image_win[0].id);
        } else if (pointSum <= 89) {
            if (countPoint == 5) {
                countPoint = countPoint + 1;
            }
            App.Services.playSound('audio_win' + App.Variables.data.data.game_image_win[1].id);
        } else {
            if (countPoint == 8) {
                countPoint = countPoint + 1;
            }
            App.Services.playSound('audio_win' + App.Variables.data.data.game_image_win[2].id);
        }
    };
    /**
     * đếm ngược thời gian
     */
    app.Services.timer = function (time) {
        if (App.Variables.setInterval.timer != null) {
            clearInterval(App.Variables.setInterval.timer);
        }
        $(".timer-record__text").text(time + "s");
        App.Variables.setInterval.timer = setInterval(function () {
            $(".timer-record__text").text(--time + "s");
            if (!time) {
                clearInterval(App.Variables.setInterval.timer);
            }
        }, 1000);
    }
    /**
     * tôi viêt lên đây đôi dòng tâm hự
     * đoạn code này rồi sẽ đi về đâu
     * khi hiện tại chìm vào trong quá khứ
     * khi màn đêm khép đôi mắt u sầu
     * thì khi đó chỉ còn chúa mỉm cười
     * vỗ vai bạn rồi trìu mến nói rằng
     * đây là đâu và tôi là ai?
     * 
     * ---- chém gió thôi -----
     */

    /**
     * phát âm thanh cuộc hội thoại theo giới tính
     * khởi chạy cuộc hội thoại
     */
    app.Services.conversation = function (male) {
        var d = 1;
        var realTime = 0;
        if (male) {
            var item = App.Variables.data.data.game_bot2_infore;
            var item2 = App.Variables.data.data.game_bot1_infore;
            startBold();
            $("#correct-text").text(item2[d].description);

            App.Services.playSound("audio_ans" + item2[d].id, function (sound) {
                realTime = sound.getDuration() || 1000;
                sound.stop();
                App.Services.timer(roundPoint(realTime / 1000) + 3);
            });

            $(".avatar-speaker img").attr("src", App.Variables.data.data.game_user_info.images);

            setTimeout(function () {
                stopBold();
                checkMarkString(textUser, item2[d].description, function (data) {
                    objScore = data;
                    showResult(data, textUser, item2[d].description, function (html) {
                        $("#speech-text").html(html);
                        setScore(data);
                    });
                    textUser = "";
                });
                setTimeout(function () {
                    App.Services.parentSpeak(d, item, item2, male)
                }, 2000);
            }, realTime + 3000);
        } else {
            var item = App.Variables.data.data.game_bot1_infore;
            var item2 = App.Variables.data.data.game_bot2_infore;
            App.Services.parentSpeak(d, item, item2, male);
        }
    };
    /**
     * thu âm user va hien kq va goi bot speak trong cuộc hội thoại
     * @param {*} d 
     * @param {*} item obj bot 1
     * @param {*} item2 obj bot 2
     * @param male : bot 1 hay bot 2
     */
    app.Services.parentSpeak = function (d, item, item2, male) {
        App.Services.speak(d, item, item2, male, function (time, dd, realTime) {
            var des = "";
            if (male) {
                des = dd + 1
            } else {
                des = dd;
            }
            var html = '<span class="bot-word">' + item[dd].description + '</span>'
            $("#correct-text").html(html);
            $("#speech-text").html("");
            $(".avatar-speaker img").attr("src", item[0].images);
            setTimeout(function () {
                if (des < item2.length) {
                    startBold();
                    App.Services.timer(roundPoint(realTime / 1000) + 3);
                    $(".avatar-speaker img").attr("src", App.Variables.data.data.game_user_info.images);
                    $("#correct-text").text(item2[des].description);
                    setTimeout(function () {
                        stopBold();
                        checkMarkString(textUser, item2[des].description, function (data) {
                            showResult(data, textUser, item2[des].description, function (html) {
                                $("#speech-text").html(html);
                                setScore(data);
                            });
                            textUser = "";
                        });
                    }, realTime + 3000);
                }
            }, time);
        });
    }
    /**
     * hàm chạy lần lượt các đoạn hội thoại của bot
     * @param {*} d : thứ tự audio 
     * @param {*} item : câu trả lời
     * @param {*} callcack: goi ham thu am sau khi bot noi
     */
    app.Services.speak = function (d, item, item2, male, callback) {
        var des = 0;
        var realTime = 0;
        if (male) {
            des = d + 1;
        } else {
            des = d;
        }
        var delay = 0;
        var text = "audio_ans" + item[d].id;
        App.Services.playSound(text, function (sound) {
            App.Variables.setInterval.time = sound.getDuration();
            delay = sound.getDuration() + 5200 || 5200;

            App.Services.playSound("audio_ans" + item2[des].id, function (sound2) {
                delay += sound2.getDuration() || 1000;
                realTime = sound2.getDuration();
                sound2.stop();
            });

            callback ? callback(App.Variables.setInterval.time, d, realTime) : "";
        });


        if (App.Variables.setInterval.event_listen != null) {
            clearInterval(App.Variables.setInterval.event_listen);
        }
        App.Variables.setInterval.event_listen = setInterval(function () {
            if (d < item.length - 1) {
                d++;
                if (male) {
                    des = d + 1;
                } else {
                    des = d;
                }
                var text = "audio_ans" + item[d].id;
                App.Services.playSound(text, function (sound) {
                    App.Variables.setInterval.time = sound.getDuration();
                    delay = sound.getDuration() + 5200 || 5200;
                    if (des < item2.length) {
                        App.Services.playSound("audio_ans" + item2[des].id, function (sound2) {
                            delay += sound2.getDuration() || 1000;
                            realTime = sound2.getDuration()
                            sound2.stop();
                        });
                    }

                    callback ? callback(App.Variables.setInterval.time, d, realTime) : "";
                });
            } else {
                numberPlay--;
                if (numberPlay) {
                    App.Services.conversation(!male);
                } else {
                    // ket thuc here
                    App.Variables.end.active(0);
                    setMainScore();
                }
                clearInterval(App.Variables.setInterval.event_listen);
            }
        }, (delay));
    };


    return app;

}(App || {}));
var App = (function (app) {
    /**
     * [enable log]
     * @type {Boolean}
     */
    app.Log.enable = false;
    app.jsonKH = false;

    return app;
}(App || {}));

$(document).ready(function () {
    document.documentElement.addEventListener('gesturestart', function (event) {
        event.preventDefault();
    }, false);
    $.ajaxSetup({
        crossOrigin: true
    });
    /**
     * let's play
     * Config Data to Mocup.json
     */
    App.Services.getDataRes(
        function (data) {
            App.Log.w(data, "ajax data");
            App.Variables.data = data;
            App.Services.registerAllSound(App.Variables.data);
            initAll();
        },
        function (err) {
            alert(err)
        }
    );
});

var male = true; // check bot1 , bot2
var isStart = false; //check bắt đầu record hay chưa
var textUser = ""; // text user speek
var textSentences = []; //Mảng gồm thông tin câu đúng, câu trả lời của người chơi và điểm.
var currentSentence = 0; //Câu hiện tại.
var numberPlay = 2 // số cuộc hội thoại
var main_score = 0; // điểm toàn bộ phần chơi
var indexAgain = 0;
var isAgain = false; // kiểm soát phát lại audio record 

//Hiển thị kết quả lên màn hình.
function showResult(data, textSpeech, correctText, callback) {
    var correctTextSplit = correctText.split(/\s+/);
    var stringHTML = '';
    var urlS = "";
    correctTextSplit.forEach(function (word, index) {
        if (data.mark[index] == 1) {
            stringHTML += '<span class="correct-word">' + word + '</span>' + ' ';
        } else {
            stringHTML += '<span class="incorrect-word">' + word + '</span>' + ' ';
        }
    });
    createObjectURLRecord(function (url) {
        if (url != undefined && textSpeech != "") {
            urlS = url;
        }
        textSentences[currentSentence] = {
            correct: correctText, //Câu đúng.
            speech: textSpeech, //Câu người chơi đọc.
            stringHTML: stringHTML, //Text đã được highlight đúng sai.
            point: data.point, //Điểm đọc câu.
            url: urlS,
        };
        currentSentence++;
    });
    callback ? callback(stringHTML) : "";
};
/**
 * nghe lai all record (new)
 */
function again(index, sound, height, callback) {
    if (index < sound.length && isAgain) {

        $(".evaluate__two__three").removeClass("evaluate__two__hover");
        $(".evaluate__two__three:eq(" + index + ")").addClass("evaluate__two__hover");
        $(".evaluate__two ").scrollTop(index * height);

        playRecordFromObj(sound[index].url,
            function () {
                setTimeout(function () {
                    again(index + 1, sound, height, callback);
                }, 1000);
            },
            function () {
                again(index + 1, sound, height, callback);
            }
        );
    } else return;
}
/**
 * nghe lại all record
 * phương pháp này ko thích hợp vì có độ trễ khi load audio
 * để đây để tham khảo thêm
 */
function listenAgain() {
    var d = 0;
    var delay;
    playRecordFromObj(textSentences[d].url, function (duration) {
        delay = duration || 0;
    });

    $(".evaluate__two__three").removeClass("evaluate__two__hover");
    $(".evaluate__two__three:eq(" + d + ")").addClass("evaluate__two__hover");
    var height = $(".evaluate__two__three").getNumbByPx("height");
    var top_lst = 0;
    $(".evaluate__two ").scrollTop(top_lst);

    if (App.Variables.setInterval.event_listen != null) {
        clearInterval(App.Variables.setInterval.event_listen);
    }

    App.Variables.setInterval.event_listen = setInterval(function () {
        if (d < textSentences.length - 1) {
            d++;

            top_lst += height;
            $(".evaluate__two ").scrollTop(top_lst);

            playRecordFromObj(textSentences[d].url, function (duration) {
                delay = duration || 0;
            });
            //hover item
            $(".evaluate__two__three").removeClass("evaluate__two__hover");
            $(".evaluate__two__three:eq(" + d + ")").addClass("evaluate__two__hover");
        } else {
            $(".bg--learn__item").removeClass("bg--learn__hover");
            top_lst = 0;
            $(".evaluate__two ").scrollTop(top_lst);
            clearInterval(App.Variables.setInterval.event_listen);
        }
    }, (delay + 1000));
};
/* 
* text: văn bản thu âm được
* source: văn bản đúng
* function callback trả về tham biến (data)
*/
function checkMarkString(text, source, callback) {
    if (source == null)
        source = "error";
    text = text.toLowerCase().replace(new RegExp('[^a-z0-9 ]', 'g'), '');
    source = source.toLowerCase().replace(new RegExp('[^a-z0-9 ]', 'g'), '');
    callback ? callback(JSON.parse(markstring(text, source))) : "";
};
//Tính điểm trung bình.
function avgPoint() {
    var sumPoint = 0;
    textSentences.forEach(function (textSentence) {
        if (typeof textSentence.point == 'number') {
            sumPoint += textSentence.point;
        }
    });
    return sumPoint / textSentences.length;
}
/**
 * làm tròn điểm truyền vào
 * @param {} point điểm 
 */
function roundPoint(point) {
    var beforePoint = Math.floor(point);
    var afterPoint = point - beforePoint;
    var delta = 0;
    if (afterPoint >= 0.5) {
        delta = 1;
    } else {
        delta = 0;
    }
    return beforePoint + delta;
}
//Gửi điểm lên server.
function upPointToServer(point, callback) {
    var dataHeaders = App.Services.GetObjectFromUrl();
    dataHeaders.point = point;
    App.Http.postHeaders(App.Constants.upPoint, dataHeaders,
        function (data) {
            App.Log.w(data, 'DATA');
            if (typeof data === "object") {
                callback ? callback(data) : "";
            } else {
                callback ? callback(JSON.parse(data)) : "";
            }
        },
        function (err) {
            App.Log.w(err, 'ERR');
            callerr(err);
        }
    );
}
/**
 * hiện đánh giá
 */
function showEval() {
    var evalElement = document.getElementById('evalansw');
    evalElement.innerHTML = '';
    textSentences.forEach(function (textSentence) {
        var stringHTML = `
      <div class="evaluate__two__three">
        <div class="evaluate--icon">${textSentence.point}</div>
        <ul>
          <li>${textSentence.stringHTML}</li>
        </ul>
      </div>`;
        evalElement.innerHTML += stringHTML;
    });
}
/**
 * load ảnh và âm thanh đầu game
 */
function load() {
    Speech.init();
    $('.bg--wrap').css('background-image', 'url(' + App.Variables.data.data.game_background[0].images + ')');
    $('.letsplay').css('background-image', 'url(' + App.Variables.data.data.game_load.images + ')');
    $('.playing').css('background-image', 'url(' + App.Variables.data.data.game_background[2].images + ')');
    $('.endgame').css('background-image', 'url(' + App.Variables.data.data.game_background[3].images + ')');
    $('.howto').css('background-image', 'url(' + App.Variables.data.data.game_background[1].images + ')');
    $('.howto__content').text(App.Variables.data.data.game_help.description);
    App.Variables.load.process.one("animationend webkitAnimationEnd oAnimationEnd", function () {
        App.Log.w("animationend :end load");
        $(".bg--play__btn").active(0);
        $(".bg--play__load").disable();
        playSoundByKey('background_music' + App.Variables.data.data.game_background_music[0].id);
    })
    App.Variables.load.process.css("animation-name", "load");
    App.Variables.load.process.one('animationstart mozAnimationStart webkitAnimationStart oAnimationStart MSAnimationStart', function () {
        var i = 0;
        if (App.Variables.setInterval.load_percent != null) {
            clearInterval(App.Variables.setInterval.load_percent);
        }
        App.Variables.setInterval.load_percent = setInterval(function () {
            if (i > 100) {
                clearInterval(App.Variables.setInterval.load_percent);
            } else {
                App.Variables.load.span.text(+ i++ + "%");
            }
        }, 58);
    });
}
/**
 * dừng âm đang chạy 
 * Chạy âm thanh theo id.
 */
function playSoundByKey(key) {
    createjs.Sound.stop();
    createjs.Sound.play(key);
}
/**
 * bắt đầu record và speech
 */
function startBold() {
    startRecorder();
    Speech.Event.start();
    isStart = true;
};
/**
 * dừng record và speech
 */
function stopBold() {
    stopRecorder();
    Speech.Event.stop();
    isStart = false;
};
/**
 * khởi tạo record và speech
 */
function initSpeechAndRecord() {
    initRecord();
    Speech.init();
    Speech.Listen.result(function (text) {
        if (text != undefined && text != "") {
            textUser = text;
        }
    });
};
/**
 * set main score
 */
function setMainScore() {
    main_score = avgPoint();
    main_score = roundPoint(main_score);
    $(".pointfinal").text(main_score);
    if (main_score < 60) {
        $(".image_win img").attr("src", App.Variables.data.data.game_image_win[0].images);
        playSoundByKey('audio_win' + App.Variables.data.data.game_image_win[0].id)
    } else if (main_score < 90) {
        $(".image_win img").attr("src", App.Variables.data.data.game_image_win[1].images);
        playSoundByKey('audio_win' + App.Variables.data.data.game_image_win[1].id)
    } else {
        $(".image_win img").attr("src", App.Variables.data.data.game_image_win[2].images);
        playSoundByKey('audio_win' + App.Variables.data.data.game_image_win[2].id)
    }
    upPointToServer(main_score);
};
/**
 * nhạc đúng sai cho từng câu
 * chưa biết đặc tả cụ thể!!!!
 */
function soundItem(score) {
    var indexS;
    if (score < 60) {
        indexS = App.Services.getNumberRandomBetween2(7, 5);
        playSoundByKey('background_music' + App.Variables.data.data.game_background_music[indexS].id)
    } else {
        indexS = App.Services.getNumberRandomBetween2(4, 2);
        playSoundByKey('background_music' + App.Variables.data.data.game_background_music[indexS].id)
    }
}
/**
 * hiểm điểm từng câu
 */
function setScore(data) {
    var score = $(".text-score");
    var bg_score = $(".img-score");
    score.text(data.point);
    bg_score.removeClass("opacity");
    setTimeout(function () {
        bg_score.addClass("opacity");
    }, 1800);
    soundItem(data.point);
};

/**
 * clearAll
 */
function clearAll() {
    clearRecorder();
    textSentences = []; //Mảng gồm thông tin câu đúng, câu trả lời của người chơi và điểm.
    currentSentence = 0; //Câu hiện tại.
    numberPlay = 2 // số cuộc hội thoại
    main_score = 0;
    indexAgain = 0;
    isAgain = false;
    isStart = false;
    if (App.Variables.setInterval.event_listen != null) {
        clearInterval(App.Variables.setInterval.event_listen);
    }
};
/**
 * chơi lại
 */
function replay() {

    clearAll();
    $("#endgame").disable();
    setTimeout(function () {
        App.Services.conversation(!male);
    }, 1000);

}
/**
 * khởi tạo toàn bộ
 */
function initAll() {
    load();
    initSpeechAndRecord();
    /**
     * click vào nút play để chơi
     */
    App.Variables.btn_play.click(function () {
        playSoundByKey('background_music' + App.Variables.data.data.game_background_music[1].id);
        App.Variables.letsplay.disable();
        App.Variables.playing.active(0);

        $(".video video").attr("src", App.Variables.data.data.game_question.video);
        $(".video video")[0].volume = 0;
        //$(".video video")[0].pause();
        $(".avatar-speaker img").attr("src",
            App.Variables.data.data.game_bot1_infore[0].images
        );
        setTimeout(function () {
            App.Services.conversation(!male);
        }, 1000);
    });
    /**
     * nút chơi lại
     */
    App.Variables.btn_replay.click(replay);
    /**
     * nút hưỡng dẫn
     */
    App.Variables.btn_howto.click(function () {
        playSoundByKey('background_music' + App.Variables.data.data.game_background_music[1].id);
        App.Variables.howto.active(0);
    });
    /**
     * quay lại màn xuất phát
     */
    App.Variables.btn_back.click(function () {
        playSoundByKey('background_music' + App.Variables.data.data.game_background_music[1].id);
        App.Variables.howto.disable();
        App.Variables.danhgia.disable();
        App.Variables.letsplay.active(0);
        clearAll();
    });
    /**
     * nút đánh giá kết quả
     */
    App.Variables.btn_danhgia.click(function () {
        App.Variables.danhgia.active(0);
        App.Variables.playing.disable();
        App.Variables.end.disable();
        App.Variables.letsplay.disable();
        showEval();
    });
    /**
     * nút nghe lại record của user
     */
    App.Variables.btn_listen.click(function () {
        isAgain = true;
        var height = $(".evaluate__two__three").getNumbByPx("height");
        again(indexAgain, textSentences, height, function () {

        });
    });
}

$(window).bind('resize load', function () {
    if ($(window).innerWidth() <= 1366) {
        $("#wrapper").scale($(window).innerWidth() / 1366);
    }
})

var source = "Hi. Tom, How are you!";
var input = "Hi Tom how are you";

var arrInput = input.toLowerCase().replace(new RegExp('[^a-z0-9 ]', 'g'), '').split(" ");
var arrSource = source.toLowerCase().replace(new RegExp('[^a-z0-9 ]', 'g'), '').split(" ");
var arrayNew = [];
var sourceTemp = source;
var index = 0;
for (var i = 0; i < arrSource.length; i++) {
    if (!sourceTemp.startsWith(arrSource[i])) {
        arrayNew[index] = sourceTemp.substring(0, 1);
        sourceTemp = sourceTemp.substring(1, sourceTemp.length);
        sourceTemp = sourceTemp.trim();
        index++;
    }
    arrayNew[index] = arrSource[i];
    index++;
    sourceTemp = sourceTemp.substring(arrSource[i].length, sourceTemp.length);    
}
utils.writeLog(arrayNew);
var array = [];
index = 0;
var counterSucc = 0;
for (var i = 0; i < arrSource.length; i++) {
    var point = 0;
    for (var j = index; j < arrInput.length; j++) {
        if (arrInput[j] == arrSource[i]) {
            index = j;
            point = 1;
            counterSucc++;
            break;
        }
    }
    array.push(point);
}
var point = Math.floor(counterSucc * 100 / arrSource.length);
var result = new Map();
utils.writeLog(array);

