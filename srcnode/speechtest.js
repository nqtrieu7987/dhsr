// record voice
var kr_audio_context;
var kr_recorder;
function startUserMedia(stream) {
    var kr_input = kr_audio_context.createMediaStreamSource(stream);
    kr_recorder = new Recorder(kr_input);
}

function startRecording() {
    kr_recorder && kr_recorder.record();
}
var audio_record = document.getElementById('audio_record');
function stopRecording() {
    kr_recorder && kr_recorder.stop();
    createDownloadLink();
    kr_recorder.clear();
}
function createDownloadLink() {
    kr_recorder && kr_recorder.exportWAV(function (blob) {
        var url = URL.createObjectURL(blob);
        $('#audio_record').attr('src', url);
    });
}
window.onload = function init() {
    try {
        // webkit shim
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia;
        window.URL = window.URL || window.webkitURL;
        kr_audio_context = new AudioContext;
        //__log('Audio context set up.');
        //__log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));
    } catch (e) {
        //alert('No web audio support in this browser!');
    }
    navigator.getUserMedia({ audio: true }, startUserMedia, function (e) {
        //__log('No live audio input: ' + e);
    });
};
// end record voice

// record text google speech
function replace_txt(str) {
    if (str.indexOf('.') > 0) {
        str = str.replace(/\./g, "");
    }
    if (str.indexOf('!') > 0) {
        str = str.replace(/!/g, "");
    }
    if (str.indexOf('?') > 0) {
        str = str.replace(/\?/g, "");
    }
    if (str.indexOf(',') > 0) {
        str = str.replace(/,/g, "");
    }
    if (str.indexOf('/') > 0) {
        str = str.replace(/\//g, "");
    }
    if (str.indexOf(':') > 0) {
        str = str.replace(/:/g, "");
    }
    if (str.indexOf('â€™') > 0) {
        str = str.replace(/'/g, "'");
    }
    if (str.indexOf('\(') > 0) {
        str = str.replace(/\(/g, "");
    }
    if (str.indexOf('\)') > 0) {
        str = str.replace(/\)/g, "");
    }
    str = str.toLowerCase();
    str = $.trim(str);
    return str;
}
/*CHAM DIEM LUYEN TAP*/
var n_score = 0;
function kr_score() {
    n_score++;
    if (n_score > 2 && !paidmember()) {
        $('#kr_none_kids').show();
    } else {
        var txt_user = replace_txt($('#krrt_text').text());
        var txt_true = $.trim($('#krrt_text').attr('result').replace(/-/g, ' ').replace(/\s+/g, ' '));
        txt_true = replace_txt(txt_true);
        $.post('/ws_action/handle.php', { string1: txt_true, string2: txt_user, type: 'smarttext' }, function (obj) {
            var data = obj.split('|');
            var persent = data[2];
            $('#krrs_number').text(persent + "%");
            if (persent <= 50) {
                $("#kr_tresult").text("Báº¡n hÃ£y lÃ m láº¡i Ä‘á»ƒ Ä‘áº¡t Ä‘iá»ƒm cao hÆ¡n nhÃ©!");
            } else if (persent > 50 && persent <= 70) {
                $("#kr_tresult").text("Báº¡n lÃ m khÃ¡ tá»‘t. Cá»‘ gáº¯ng hÆ¡n ná»¯a nhÃ©!");
            } else if (persent > 70 && persent <= 90) {
                $("#kr_tresult").text("Báº¡n lÃ m ráº¥t tá»‘t. Cá»‘ gáº¯ng thÃªm Ä‘á»ƒ Ä‘áº¡t 100% nhÃ©.");
            } else if (persent > 90) {
                $("#kr_tresult").text("Báº¡n ráº¥t xuáº¥t sáº¯c. Cá»‘ gáº¯ng phÃ¡t huy nhÃ©!");
            }

        });
    }
}
(function ($) {
    // function wave
    $.fn.wave_random = function (options) {
        var defaults = ({ $top_min: 0, $top_max: 25 });
        var settings = $.extend({}, defaults, options);
        var _self = $(this);
        var _top;
        (function loop_wave() {
            _top = Math.floor(Math.random() * (settings.$top_max - settings.$top_min + 1)) + settings.$top_min;
            _self.stop(true).animate({ top: _top }, 200, 'linear', function () {
                loop_wave();
            })
        })()
    }
    var cvd_record = {
        id_box: "box_record",
        html_record: '<div id="record"></div><div class="ge" id="kr_arrecord">\
						<div class="ge" id="kr_uparrow"></div>\
						<div class="ge" id="kr_none_kids">Báº¡n pháº£i lÃ  <a href="/huong-dan/214-quyen-loi-thanh-vien-vip-cua-tienganh123.html" target="_blank" title="quyá»n lá»£i thÃ nh viÃªn VIP" style="color:orange; text-decoration:underline">thÃ nh viÃªn VIP cá»§a tienganh123.com</a> má»›i Ä‘Æ°á»£c cháº¥m Ä‘iá»ƒm.</div>\
						<div class="ge" id="kr_arresult">\
						<div class="ge" id="krr_area">\
						<div class="ge" id="krr_ispeak" style="display: block;"></div>\
						<div class="ge" id="krr_arwave" style="display: none;">\
						<div class="ge krrw_wave" style="top: 0px;"></div>\
						<div class="ge krrw_wave" style="top: 0px;"></div>\
						<div class="ge krrw_wave" style="top: 0px;"></div>\
						</div>\
						<div class="ge" id="krr_arscore" style="display: none;">\
						<div class="ge" id="krrs_text">Báº¡n Äáº¡t</div>\
						<div class="ge" id="krrs_number">0%</div>\
						</div>\
						</div>\
						<div class="ge" id="krr_verline" style="display: none;"></div>\
						<div class="ge" id="krr_artext">\
						<div class="ge" id="krrt_bgtext">\
						<div class="ge" id="krrt_ins" style="display: table-cell;">Báº¡n hÃ£y kÃ­ch chuá»™t vÃ o biá»ƒu tÆ°á»£ng micro hoáº·c áº¥n phÃ­m Enter Ä‘á»ƒ báº¯t Ä‘áº§u ghi Ã¢m...</div>\
						<div class="ge" id="krrt_text" result="hi"></div>\
						</div>\
						</div>\
						</div>\
						<div class="ge" id="kr_tresult" style="display: none;">Báº¡n hÃ£y lÃ m láº¡i Ä‘á»ƒ Ä‘áº¡t Ä‘iá»ƒm cao hÆ¡n nhÃ©!</div>\
						<div class="ge" id="kr_arbutton">\
						<div class="ge" id="krb_arrecord" style="display: none;">\
						<div class="ge" id="krbre_icon"></div>\
						<div class="ge" id="krbre_text">Ghi Ã¢m láº¡i (enter)</div>\
						</div>\
						<div class="ge" id="krb_arspeak" style="display: none;">\
						<div class="ge" id="krbsp_icon"></div>\
						<div class="ge" id="krbsp_text">Nghe láº¡i bÃ i ghi Ã¢m</div>\
						</div>\
						<div class="ge act" id="krb_arstop" style="display: none;">\
						<div class="ge" id="krbst_icon"></div>\
						<div class="ge" id="krbst_text">Dá»«ng ghi Ã¢m (enter)</div>\
						</div>\
						</div>\
						<div class="ge" id="kr_bclose"></div>\
						<div class="ge" id="kr_araudio">\
						<div class="ge jp-audio" id="kr_audio" src="" theme="circle" style="width: 0px; height: 0px;"><img id="jp_poster_0" style="width: 0px; height: 0px; display: none;"><audio id="jp_audio_0" preload="auto" src=""></audio></div>\
						<div id="jp_container_kr_audio" class="jp-audio jpcircle"><div class="jp-type-single"><div class="jp-gui jp-interface jp-circle"><ul class="jp-controls"><li> <a class="jp-play" tabindex="1" href="javascript:;">play</a> </li><li> <a class="jp-pause" tabindex="1" href="javascript:;" style="display: none;">pause</a> </li><ul></ul></ul></div></div></div></div>\
						</div>',
        id_info: 'krrt_ins',
        txt_upgrade: '(Chá»©c nÄƒng ghi Ã¢m hiá»‡n táº¡i chÆ°a Ä‘Æ°á»£c trÃ¬nh duyá»‡t nÃ y há»— trá»£. Báº¡n hÃ£y dÃ¹ng <a href= "//www.google.com/chrome">Chrome</a> phiÃªn báº£n 25 trá»Ÿ lÃªn.)',
        txt_ins: 'Báº¡n hÃ£y kÃ­ch chuá»™t vÃ o biá»ƒu tÆ°á»£ng micro hoáº·c áº¥n phÃ­m Enter Ä‘á»ƒ báº¯t Ä‘áº§u ghi Ã¢m...',
        txt_speak_now: 'Äang ghi Ã¢m - HÃ£y Ä‘á»c bÃ i ghi Ã¢m cá»§a báº¡n',
        txt_no_microphone: '(No microphone was found. Ensure that a microphone is installed and that<a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">microphone settings</a> are configured correctly.)',
        txt_blocked: "(Permission to use microphone is blocked. To change, go to  chrome://settings/contentExceptions#media-stream)",
        txt_denied: "(Permission to use microphone was denied.)",
        txt_start: " (Báº¡n hÃ£y kÃ­ch vÃ o nÃºt micro Ä‘á»ƒ thu Ã¢m.)",
        txt_no_speech: "KhÃ´ng nháº­n Ä‘Æ°á»£c giá»ng nÃ³i. Kiá»ƒm tra láº¡i headphone cá»§a báº¡n.",
        recognizing: false,
        ignore_onend: false,
        id_start: "krr_ispeak",
        final_transcript: '',
        text_record: "krrt_text",
        start_timestamp: 0,
        showInfo: function (str) {
            document.getElementById(cvd_record.id_info).innerHTML = str;
        }
    },
        _speech = {
            init: function (option) {
                $('#' + cvd_record.id_box).append(cvd_record.html_record);
                window.___gcfg = { lang: 'en' };
                $.extend(cvd_record, option);
                if (!('webkitSpeechRecognition' in window)) {
                    cvd_record.showInfo(cvd_record.txt_upgrade);
                    $('#kr_bclose').unbind('click').bind('click', function () {
                        $('.bg_yellow').removeClass('bg_yellow');
                        $('.kr_bgyellow').removeClass('kr_bgyellow');
                        $('#kr_arrecord').hide();
                    });
                } else {
                    var recognition = new webkitSpeechRecognition();
                    recognition.continuous = true;
                    recognition.lang = 'en-IN';
                    recognition.interimResults = true;
                    var start_record = document.getElementById(cvd_record.id_start);
                    var result_text = document.getElementById(cvd_record.text_record);
                    recognition.onstart = function () {
                        cvd_record.recognizing = true;
                        cvd_record.showInfo(cvd_record.txt_speak_now);
                        //start_record.src = '/file/common/lesson/real_class/img/mic-animate.gif';
                        //start_record.classList.add("is_recording");
                        $('#text_record').html('');
                        $('#krrt_text').html('');
                    };

                    recognition.onerror = function (event) {
                        $('#krb_arrecord').css('display', 'inline-block');
                        $('#krb_arstop').css('display', 'none');
                        $('.krrw_wave').each(function (index, ele) {
                            $(ele).stop(true).css('top', '0px');
                        })
                        if (!$("#krb_arstop").hasClass('act')) {
                            $("#krb_arstop").addClass('act');
                        }
                        if (event.error == 'no-speech') {
                            utils.writeLog('no-speech');
                            $('#krrt_ins').text(cvd_record.txt_no_speech);
                            //start_record.src = '/file/common/lesson/real_class/img/mic.gif';
                            //start_record.classList.remove("is_recording");
                            cvd_record.showInfo(cvd_record.txt_no_speech);
                            cvd_record.ignore_onend = true;
                        }

                        if (event.error == 'audio-capture') {
                            utils.writeLog('no-microphone');
                            $('#krrt_ins').text(cvd_record.txt_no_microphone);
                            //start_record.src = '/file/common/lesson/real_class/img/mic.gif';
                            //start_record.classList.remove("is_recording");
                            cvd_record.showInfo(cvd_record.txt_no_microphone);
                            cvd_record.ignore_onend = true;
                        }
                        if (event.error == 'not-allowed') {
                            utils.writeLog('not-allowed');
                            if (event.timeStamp - cvd_record.start_timestamp < 100) {
                                utils.writeLog('blocked');
                                $('#krrt_ins').text(cvd_record.txt_blocked);
                                cvd_record.showInfo(cvd_record.txt_blocked);
                            } else {
                                utils.writeLog('denied');
                                $('#krrt_ins').text(cvd_record.txt_denied);
                                cvd_record.showInfo(cvd_record.txt_denied);
                            }
                            cvd_record.ignore_onend = true;
                        }

                    };
                    recognition.onend = function () {
                        cvd_record.recognizing = false;
                        if (cvd_record.ignore_onend) {
                            return;
                        }
                        $('.krrw_wave').each(function (index, ele) {
                            $(ele).stop(true).css('top', '0px');
                        })
                        if (!$("#krb_arstop").hasClass('act')) {
                            recognition.stop();
                            kr_score();
                            stopRecording();
                            //utils.writeLog('reco end');
                            $('#krr_arwave, #krb_arstop, #krrt_ins').css('display', 'none');
                            $('#krr_arscore, #kr_tresult').css('display', 'block');
                            $('#krr_verline, #krb_arrecord, #krb_arspeak').css('display', 'inline-block');
                        }

                        //start_record.classList.remove("is_recording");				
                        if (window.getSelection) {
                            window.getSelection().removeAllRanges();
                            var range = document.createRange();
                            range.selectNode(result_text);
                            window.getSelection().addRange(range);
                        }
                    };

                    recognition.onresult = function (event) {
                        $('#krrt_ins').css('display', 'none');
                        var interimTranscripts = '';
                        if (typeof (event.results) == 'undefined') {
                            recognition.onend = null;
                            recognition.stop();
                            cvd_record.showInfo(cvd_record.id_upgrade);
                            return;
                        }
                        for (var i = event.resultIndex; i < event.results.length; ++i) {
                            if (event.results[i].isFinal) {
                                cvd_record.final_transcript += event.results[i][0].transcript;
                            }
                        }
                        for (var i = event.resultIndex; i < event.results.length; i++) {
                            var transcript = event.results[i][0].transcript;
                            transcript.replace("\n", "<br>");
                            if (event.results[i].isFinal) {
                                finalTranscripts += transcript;
                            } else {
                                interimTranscripts += transcript;
                            }
                        }
                        if ($("#krb_arstop").hasClass('mode_click')) {
                            result_text.innerHTML = cvd_record.final_transcript + '<span>' + interimTranscripts + '</span>';
                        }

                        if (replace_txt($('#krrt_text').text()) == replace_txt($.trim($('#krrt_text').attr('result')))) {
                            $('#krb_arstop').trigger('click');
                        }
                    };




                    
                    speech.recognition.onresult = function(event){
                        var current = event.resultIndex;
                          var transcript = event.results[current][0].transcript;
                            var mobileRepeatBug = (current == 1 && transcript == event.results[0][0].transcript);
                            
                          if(!mobileRepeatBug) {
                            callback(transcript)
                      }
                    };




                    //if(kr_gamefocused == true ){
                    $(document).keyup(function (e) {
                        if ($('#krb_arstop').css('display') == 'inline-block') {
                            if (e.keyCode == 13) {
                                $('#krb_arstop').trigger('click');
                            }
                        } else if ($('#krb_arrecord').css('display') == 'inline-block') {
                            if (e.keyCode == 13) {
                                $('#krb_arrecord').trigger('click');
                            }
                        } else if ($('#krr_ispeak').css('display') == 'block') {
                            if (e.keyCode == 13) {
                                $('#krr_ispeak').trigger('click');
                            }
                        }
                    })
                    //}
                    $("#krb_arstop").unbind('click').bind('click', function () {
                        if ($("#krb_arstop").hasClass('mode_click')) {
                            $("#krb_arstop").removeClass('mode_click');
                            if (!$("#krb_arstop").hasClass('act')) {
                                $("#krb_arstop").addClass('act');
                            }
                            recognition.stop();
                            kr_score();
                            stopRecording();
                            //utils.writeLog('stop click');
                            $('#krr_arwave, #krb_arstop, #krrt_ins').css('display', 'none');
                            $('#krr_arscore, #kr_tresult').css('display', 'block');
                            $('#krr_verline, #krb_arrecord, #krb_arspeak').css('display', 'inline-block');
                        }
                    });
                    $('#krb_arrecord').unbind('click').bind('click', function () {
                        $("#krb_arstop").addClass('mode_click');
                        $('#krr_verline, #krb_arrecord, #krb_arspeak, #krr_arscore, #kr_tresult').css('display', 'none');
                        $('#krrt_ins').css('display', 'table-cell');
                        $('#krr_arwave, #krb_arstop').css('display', 'block');
                        $('#krb_arstop').css('display', 'inline-block');
                        $('#krrt_text').html("");
                        $('#krrs_number, #kr_tresult').text('');
                        cvd_record.final_transcript = '';
                        cvd_record.ignore_onend = false;
                        recognition.start();
                        startRecording();
                        cvd_record.start_timestamp = event.timeStamp;
                        $('.krrw_wave').each(function (index, ele) {
                            $(ele).wave_random();
                        })
                        audio_record.pause();
                        $('#krbsp_icon').removeClass('krbsp_icon_active');
                        $("#krb_arstop").removeClass('act');
                    });
                    $('#krb_arspeak').unbind('click').bind('click', function () {
                        audio_record.play();
                        $('#krbsp_icon').addClass('krbsp_icon_active');
                        //$('#krbsp_icon').kfwspm({fps: 2, matrix_frames:[[1,1],[1,2]]});
                    });

                    $('#kr_araudio').unbind('click').bind('click', function () {
                        if (!$("#krb_arstop").hasClass('act')) {
                            $("#krb_arstop").addClass('act');
                        }
                        recognition.stop();
                    });
                    $('#kr_bclose').unbind('click').bind('click', function () {
                        audio_record.pause();
                        if (cvd_record.recognizing) {
                            recognition.stop();
                        }
                        $('.bg_yellow').removeClass('bg_yellow');
                        $('.kr_bgyellow').removeClass('kr_bgyellow');
                        $('#kr_arrecord').hide();
                        $('#krrt_text').text('');
                        $('.status_cham_diem').text('');
                    });
                    $('#krr_ispeak').unbind('click').bind('click', function () {
                        $(this).css('display', 'none');
                        utils.writeLog('click');
                        $('#krb_arrecord').trigger('click');
                        $("#krb_arstop").removeClass('act');

                        $('.krrw_wave').each(function (index, ele) {
                            $(ele).wave_random();
                        })
                        $('#krrt_text').attr('result', txt);
                    });
                }
            }

        };
    $.fn.speech_chrome = function (option) {
        if (typeof option === 'object' || !option) {
            return _speech.init.apply(this, arguments);
        } else {
            $.error("method " + option + ' does not exist not jQuery');
        }
    }
})(jQuery);

// record voice	
(function (f) { if (typeof exports === "object" && typeof module !== "undefined") { module.exports = f() } else if (typeof define === "function" && define.amd) { define([], f) } else { var g; if (typeof window !== "undefined") { g = window } else if (typeof global !== "undefined") { g = global } else if (typeof self !== "undefined") { g = self } else { g = this } g.Recorder = f() } })(function () {
    var define, module, exports; return (function e(t, n, r) { function s(o, u) { if (!n[o]) { if (!t[o]) { var a = typeof require == "function" && require; if (!u && a) return a(o, !0); if (i) return i(o, !0); var f = new Error("Cannot find module '" + o + "'"); throw f.code = "MODULE_NOT_FOUND", f } var l = n[o] = { exports: {} }; t[o][0].call(l.exports, function (e) { var n = t[o][1][e]; return s(n ? n : e) }, l, l.exports, e, t, n, r) } return n[o].exports } var i = typeof require == "function" && require; for (var o = 0; o < r.length; o++)s(r[o]); return s })({
        1: [function (require, module, exports) {
            "use strict";

            module.exports = require("./recorder").Recorder;

        }, { "./recorder": 2 }], 2: [function (require, module, exports) {
            'use strict';

            var _createClass = (function () {
                function defineProperties(target, props) {
                    for (var i = 0; i < props.length; i++) {
                        var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor);
                    }
                } return function (Constructor, protoProps, staticProps) {
                    if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor;
                };
            })();

            Object.defineProperty(exports, "__esModule", {
                value: true
            });
            exports.Recorder = undefined;

            var _inlineWorker = require('inline-worker');

            var _inlineWorker2 = _interopRequireDefault(_inlineWorker);

            function _interopRequireDefault(obj) {
                return obj && obj.__esModule ? obj : { default: obj };
            }

            function _classCallCheck(instance, Constructor) {
                if (!(instance instanceof Constructor)) {
                    throw new TypeError("Cannot call a class as a function");
                }
            }

            var Recorder = exports.Recorder = (function () {
                function Recorder(source, cfg) {
                    var _this = this;

                    _classCallCheck(this, Recorder);

                    this.config = {
                        bufferLen: 4096,
                        numChannels: 2,
                        mimeType: 'audio/wav'
                    };
                    this.recording = false;
                    this.callbacks = {
                        getBuffer: [],
                        exportWAV: []
                    };

                    Object.assign(this.config, cfg);
                    this.context = source.context;
                    this.node = (this.context.createScriptProcessor || this.context.createJavaScriptNode).call(this.context, this.config.bufferLen, this.config.numChannels, this.config.numChannels);

                    this.node.onaudioprocess = function (e) {
                        if (!_this.recording) return;

                        var buffer = [];
                        for (var channel = 0; channel < _this.config.numChannels; channel++) {
                            buffer.push(e.inputBuffer.getChannelData(channel));
                        }
                        _this.worker.postMessage({
                            command: 'record',
                            buffer: buffer
                        });
                    };

                    source.connect(this.node);
                    this.node.connect(this.context.destination); //this should not be necessary

                    var self = {};
                    this.worker = new _inlineWorker2.default(function () {
                        var recLength = 0,
                            recBuffers = [],
                            sampleRate = undefined,
                            numChannels = undefined;

                        self.onmessage = function (e) {
                            switch (e.data.command) {
                                case 'init':
                                    init(e.data.config);
                                    break;
                                case 'record':
                                    record(e.data.buffer);
                                    break;
                                case 'exportWAV':
                                    exportWAV(e.data.type);
                                    break;
                                case 'getBuffer':
                                    getBuffer();
                                    break;
                                case 'clear':
                                    clear();
                                    break;
                            }
                        };

                        function init(config) {
                            sampleRate = config.sampleRate;
                            numChannels = config.numChannels;
                            initBuffers();
                        }

                        function record(inputBuffer) {
                            for (var channel = 0; channel < numChannels; channel++) {
                                recBuffers[channel].push(inputBuffer[channel]);
                            }
                            recLength += inputBuffer[0].length;
                        }

                        function exportWAV(type) {
                            var buffers = [];
                            for (var channel = 0; channel < numChannels; channel++) {
                                buffers.push(mergeBuffers(recBuffers[channel], recLength));
                            }
                            var interleaved = undefined;
                            if (numChannels === 2) {
                                interleaved = interleave(buffers[0], buffers[1]);
                            } else {
                                interleaved = buffers[0];
                            }
                            var dataview = encodeWAV(interleaved);
                            var audioBlob = new Blob([dataview], { type: type });

                            self.postMessage({ command: 'exportWAV', data: audioBlob });
                        }

                        function getBuffer() {
                            var buffers = [];
                            for (var channel = 0; channel < numChannels; channel++) {
                                buffers.push(mergeBuffers(recBuffers[channel], recLength));
                            }
                            self.postMessage({ command: 'getBuffer', data: buffers });
                        }

                        function clear() {
                            recLength = 0;
                            recBuffers = [];
                            initBuffers();
                        }

                        function initBuffers() {
                            for (var channel = 0; channel < numChannels; channel++) {
                                recBuffers[channel] = [];
                            }
                        }

                        function mergeBuffers(recBuffers, recLength) {
                            var result = new Float32Array(recLength);
                            var offset = 0;
                            for (var i = 0; i < recBuffers.length; i++) {
                                result.set(recBuffers[i], offset);
                                offset += recBuffers[i].length;
                            }
                            return result;
                        }

                        function interleave(inputL, inputR) {
                            var length = inputL.length + inputR.length;
                            var result = new Float32Array(length);

                            var index = 0,
                                inputIndex = 0;

                            while (index < length) {
                                result[index++] = inputL[inputIndex];
                                result[index++] = inputR[inputIndex];
                                inputIndex++;
                            }
                            return result;
                        }

                        function floatTo16BitPCM(output, offset, input) {
                            for (var i = 0; i < input.length; i++ , offset += 2) {
                                var s = Math.max(-1, Math.min(1, input[i]));
                                output.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
                            }
                        }

                        function writeString(view, offset, string) {
                            for (var i = 0; i < string.length; i++) {
                                view.setUint8(offset + i, string.charCodeAt(i));
                            }
                        }

                        function encodeWAV(samples) {
                            var buffer = new ArrayBuffer(44 + samples.length * 2);
                            var view = new DataView(buffer);

                            /* RIFF identifier */
                            writeString(view, 0, 'RIFF');
                            /* RIFF chunk length */
                            view.setUint32(4, 36 + samples.length * 2, true);
                            /* RIFF type */
                            writeString(view, 8, 'WAVE');
                            /* format chunk identifier */
                            writeString(view, 12, 'fmt ');
                            /* format chunk length */
                            view.setUint32(16, 16, true);
                            /* sample format (raw) */
                            view.setUint16(20, 1, true);
                            /* channel count */
                            view.setUint16(22, numChannels, true);
                            /* sample rate */
                            view.setUint32(24, sampleRate, true);
                            /* byte rate (sample rate * block align) */
                            view.setUint32(28, sampleRate * 4, true);
                            /* block align (channel count * bytes per sample) */
                            view.setUint16(32, numChannels * 2, true);
                            /* bits per sample */
                            view.setUint16(34, 16, true);
                            /* data chunk identifier */
                            writeString(view, 36, 'data');
                            /* data chunk length */
                            view.setUint32(40, samples.length * 2, true);

                            floatTo16BitPCM(view, 44, samples);

                            return view;
                        }
                    }, self);

                    this.worker.postMessage({
                        command: 'init',
                        config: {
                            sampleRate: this.context.sampleRate,
                            numChannels: this.config.numChannels
                        }
                    });

                    this.worker.onmessage = function (e) {
                        var cb = _this.callbacks[e.data.command].pop();
                        if (typeof cb == 'function') {
                            cb(e.data.data);
                        }
                    };
                }

                _createClass(Recorder, [{
                    key: 'record',
                    value: function record() {
                        this.recording = true;
                    }
                }, {
                    key: 'stop',
                    value: function stop() {
                        this.recording = false;
                    }
                }, {
                    key: 'clear',
                    value: function clear() {
                        this.worker.postMessage({ command: 'clear' });
                    }
                }, {
                    key: 'getBuffer',
                    value: function getBuffer(cb) {
                        cb = cb || this.config.callback;
                        if (!cb) throw new Error('Callback not set');

                        this.callbacks.getBuffer.push(cb);

                        this.worker.postMessage({ command: 'getBuffer' });
                    }
                }, {
                    key: 'exportWAV',
                    value: function exportWAV(cb, mimeType) {
                        mimeType = mimeType || this.config.mimeType;
                        cb = cb || this.config.callback;
                        if (!cb) throw new Error('Callback not set');

                        this.callbacks.exportWAV.push(cb);

                        this.worker.postMessage({
                            command: 'exportWAV',
                            type: mimeType
                        });
                    }
                }], [{
                    key: 'forceDownload',
                    value: function forceDownload(blob, filename) {
                        var url = (window.URL || window.webkitURL).createObjectURL(blob);
                        var link = window.document.createElement('a');
                        link.href = url;
                        link.download = filename || 'output.wav';
                        var click = document.createEvent("Event");
                        click.initEvent("click", true, true);
                        link.dispatchEvent(click);
                    }
                }]);

                return Recorder;
            })();

            exports.default = Recorder;

        }, { "inline-worker": 3 }], 3: [function (require, module, exports) {
            "use strict";

            module.exports = require("./inline-worker");
        }, { "./inline-worker": 4 }], 4: [function (require, module, exports) {
            (function (global) {
                "use strict";

                var _createClass = (function () { function defineProperties(target, props) { for (var key in props) { var prop = props[key]; prop.configurable = true; if (prop.value) prop.writable = true; } Object.defineProperties(target, props); } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; })();

                var _classCallCheck = function (instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } };

                var WORKER_ENABLED = !!(global === global.window && global.URL && global.Blob && global.Worker);

                var InlineWorker = (function () {
                    function InlineWorker(func, self) {
                        var _this = this;

                        _classCallCheck(this, InlineWorker);

                        if (WORKER_ENABLED) {
                            var functionBody = func.toString().trim().match(/^function\s*\w*\s*\([\w\s,]*\)\s*{([\w\W]*?)}$/)[1];
                            var url = global.URL.createObjectURL(new global.Blob([functionBody], { type: "text/javascript" }));

                            return new global.Worker(url);
                        }

                        this.self = self;
                        this.self.postMessage = function (data) {
                            setTimeout(function () {
                                _this.onmessage({ data: data });
                            }, 0);
                        };

                        setTimeout(function () {
                            func.call(self);
                        }, 0);
                    }

                    _createClass(InlineWorker, {
                        postMessage: {
                            value: function postMessage(data) {
                                var _this = this;

                                setTimeout(function () {
                                    _this.self.onmessage({ data: data });
                                }, 0);
                            }
                        }
                    });

                    return InlineWorker;
                })();

                module.exports = InlineWorker;
            }).call(this, typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
        }, {}]
    }, {}, [1])(1)
});
