<button id="toggleModal" class="btn rounded-circle position-fixed" style="width: 60px; height: 60px; z-index: 9999; background-color:#11B76B; color:#FFFFFF; bottom: 1.5rem; right: 1.5rem;">
    <i class="fas fa-comments"></i>
    <i class="fas fa-times" style="display: none"></i>
</button>

<div id="modalContainer" class="position-fixed bg-white rounded shadow d-flex flex-column overflow-hidden" style="visibility: hidden; opacity: 0; transform: scale(0.95); transition: all 0.4s ease;">
    <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
        <button id="zoomModal" class="btn btn-link p-0 text-dark">
            <i class="fa fa-expand"></i>
        </button>
        <div class="d-flex align-items-center bg-white rounded px-2 py-1 shadow-sm">
            <img src="/uploads/image.png" alt="ChatBot logo" class="rounded-circle" width="32" height="32" />
            <span class="font-weight-semibold ml-2">Tú Phương AI</span>
        </div>
        <button id="minimizeModal" class="btn btn-link p-0 text-dark">
            <i class="fas fa-minus"></i>
        </button>
    </div>

    <div id="chatbotDocumentContent" class="flex-grow-1 overflow-auto px-3 py-4" style="flex: 1 1 auto;">
        <div id="chatbotIntro" class="d-flex align-items-start mb-3">
            <img src="/uploads/image.png" alt="Bot icon" class="rounded-circle mr-2" width="32" height="32" />
            <div class="bg-white rounded px-3 py-2 shadow-sm" style="max-width: 75%;">Xin chào quản trị viên, bạn cần hỏi gì?</div>
        </div>
    </div>

    <div class="d-flex align-items-center border border-primary rounded-pill mx-3 my-3 px-3 py-2">
        <input id="chatBotDocumentInput" type="text" class="form-control border-0 mr-2 custom-no-focus" placeholder="Nhập câu hỏi với tài liệu..." />
        <button id="sendMessageChatbotDocument" type="button" class="btn btn-link text-secondary p-0">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>

    <div class="d-flex justify-content-center text-center text-muted small pb-3">
        <span>Được phát triển bởi&nbsp;<strong>tubeca</strong></span>
    </div>
</div>

@section('styles')
<style>
    .chat-body::-webkit-scrollbar {
        width: 6px;
    }

    .chat-body::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 3px;
    }

    #modalContainer {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        width: 450px;
        height: 600px;
        z-index: 9999;
        bottom: 90px;
        right: 1.5rem;
        border-radius: 12px;
        visibility: hidden;
        opacity: 0;
        transform: scale(0.95);
        transition: all 0.4s ease;
        position: fixed;
        background-color: white;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    #modalContainer.scale-effect {
        transform: translate(-50%, -50%) scale(0.95);
    }

    #modalContainer.fullscreen {
        width: 100% !important;
        height: 100% !important;
        top: 0;
        left: 0;
        transform: none;
        border-radius: 0 !important;
    }

    .custom-no-focus:focus {
        box-shadow: none !important;
        outline: none !important;
    }
</style>


<script>
    // Toggle modal visibility
    document.getElementById('toggleModal').addEventListener('click', function() {
        const modal = document.getElementById('modalContainer');
        const commentIcon = this.querySelector('.fa-comments');
        const closeIcon = this.querySelector('.fa-times');
        if (modal.style.visibility === 'hidden') {
            modal.style.visibility = 'visible';
            modal.style.opacity = '1';
            modal.style.transform = 'scale(1)';
            commentIcon.style.display = 'none';
            closeIcon.style.display = 'inline';
        } else {
            modal.style.visibility = 'hidden';
            modal.style.opacity = '0';
            modal.style.transform = 'scale(0.95)';
            commentIcon.style.display = 'inline';
            closeIcon.style.display = 'none';
        }
    });

    // Zoom modal to fullscreen
    document.getElementById('zoomModal').addEventListener('click', function() {
        const modal = document.getElementById('modalContainer');
        modal.classList.toggle('fullscreen');
    });

    // Minimize modal
    document.getElementById('minimizeModal').addEventListener('click', function() {
        const modal = document.getElementById('modalContainer');
        const commentIcon = document.getElementById('toggleModal').querySelector('.fa-comments');
        const closeIcon = document.getElementById('toggleModal').querySelector('.fa-times');
        modal.style.visibility = 'hidden';
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        commentIcon.style.display = 'inline';
        closeIcon.style.display = 'none';
    });
</script>

@section('scriptss')
    <script src="{{asset("assets/plugins/chart.js/Chart.min.js")}}"></script>
    <script>
        $(document).ready(function() {

            var chartInstances = [];

            $("#sendMessageChatbotDocument").click(function() {
                sendChatbotMessage();
            });

            $("#chatBotDocumentInput").keypress(function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    sendChatbotMessage();
                }
            });

            function sendChatbotMessage() {
                let userMessage = $("#chatBotDocumentInput").val();
                if (userMessage === "") {
                    toastr.error("Vui lòng nhập câu hỏi");
                    return;
                }

                $("#chatbotIntro").remove();
                let userBubble =
                `<div class="d-flex justify-content-end mb-1">
                    <div class="bg-primary text-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">${userMessage}</div>
                </div>`;
                $("#chatbotDocumentContent").append(userBubble);
                $("#chatbotDocumentContent").animate({
                    scrollTop: $('#chatbotDocumentContent')[0].scrollHeight
                }, 500);

                $("#chatBotDocumentInput").val("");
                $.ajax({
                    url: "/admin/ask-ai-admin",
                    type: "POST",
                    data: {
                        question: userMessage
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        let loadingBubble = `
                        <div id="loadingMessage" class="d-flex align-items-start mr-3">
                            <img src="/uploads/image.png" alt="Bot icon" class="rounded-circle mr-2" width="32" height="32" />
                            <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%"><i class="fas fa-spinner fa-spin"></i></div>
                        </div>`;
                        $("#chatbotDocumentContent").append(loadingBubble);
                        $("#chatbotDocumentContent").animate({
                            scrollTop: $('#chatbotDocumentContent')[0].scrollHeight
                        }, 500);
                    },
                    success: function(response) {
                        $("#loadingMessage").remove();
                        let botMessage = response.answer;
                        botMessage = botMessage.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        botMessage = botMessage.replace(/\*(.*?)\*/g, '<em>$1</em>');
                        let botBubble = `
                        <div class="d-flex align-items-start mb-3 flex-column">
                            <div class="d-flex align-items-start">
                                <img src="/uploads/image.png" alt="Bot icon" class="rounded-circle mr-2" width="32" height="32" />
                                <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%;">${botMessage}</div>
                            </div>
                            ${response.chart && response.chart.labels.length > 0 ? `
                            <div class="mt-2" style="width: 75%;">
                                <canvas class="myChart" height="400"></canvas>
                            </div>
                            ` : ''}
                        </div>`;
                        $("#chatbotDocumentContent").append(botBubble);

                        if (response.chart && response.chart.labels.length > 0) {
                            const ctx = $("#chatbotDocumentContent").find('canvas.myChart').last()[0].getContext('2d');

                            var barChartData = {
                                labels: response.chart.labels,
                                datasets: [{
                                    label: 'Thống kê',
                                    backgroundColor: [
                                        'rgba(17, 183, 107, 0.5)',
                                        'rgba(255, 99, 132, 0.5)',   
                                        'rgba(54, 162, 235, 0.5)',  
                                        'rgba(255, 206, 86, 0.5)',  
                                        'rgba(153, 102, 255, 0.5)', 
                                        'rgba(255, 159, 64, 0.5)',  
                                        'rgba(100, 181, 246, 0.5)', 
                                        'rgba(0, 200, 190, 0.5)'  
                                    ],
                                    borderColor: [
                                        'rgba(17, 183, 107, 1)',
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)',
                                        'rgba(100, 181, 246, 1)',
                                        'rgba(0, 200, 190, 1)'
                                    ],
                                    borderWidth: 1,
                                    data: response.chart.data,
                                }]
                            };

                            var barChartOptions = {
                                maintainAspectRatio: false,
                                responsive: true,
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'BIỂU ĐỒ THỐNG KÊ'
                                },
                                scales: {
                                    xAxes: [{
                                        gridLines: {
                                            display: false,
                                        },
                                        ticks: {
                                            maxRotation: 60,
                                            minRotation: 45,
                                            fontSize: 12
                                        },
                                        barPercentage: 0.5,
                                        categoryPercentage: 0.7,
                                        scaleLabel: {
                                            display: true,
                                            fontStyle: 'bold'
                                        }
                                    }],
                                    yAxes: [{
                                        gridLines: {
                                            display: true,
                                            color: 'rgba(0,0,0,0.05)'
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            min: 0,
                                            stepSize: 10,
                                            suggestedMax: Math.max(...response.chart.data) + 2,
                                            fontSize: 12
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'GIÁ TRỊ',
                                            fontStyle: 'bold'
                                        }
                                    }]
                                },
                                tooltips: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    titleFontSize: 14,
                                    bodyFontSize: 12,
                                    xPadding: 10,
                                    yPadding: 10,
                                }
                            };

                            var newChart = new Chart(ctx, {
                                type: 'bar',
                                data: barChartData,
                                options: barChartOptions
                            });
                            chartInstances.push(newChart);
                        }

                        else {
                            $('#chartContainer').hide();
                        }

                        $("#chatbotDocumentContent").animate({
                            scrollTop: $('#chatbotDocumentContent')[0].scrollHeight
                        }, 500);
                    },
                    error: function() {
                        $("#loadingMessage").remove();
                        toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                    }
                });
            }
        });
    </script>
@endsection