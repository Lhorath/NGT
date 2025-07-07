<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Request Tool</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #111827; /* Dark background */
            color: #d1d5db; /* Light text */
        }
        .request-input {
            background-color: #1f2937;
            border-color: #4b5563;
        }
        .request-input:focus {
            background-color: #374151;
            border-color: #6366f1;
            outline: none;
            box-shadow: none;
        }
        .tab-active {
            border-bottom-color: #6366f1;
            color: #e5e7eb;
        }
        .tab-inactive {
            border-bottom-color: transparent;
            color: #9ca3af;
        }
        .side-panel-item:hover {
            background-color: #374151;
        }
        .loader {
            border-top-color: #6366f1;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</head>
<body class="h-screen flex flex-col md:flex-row p-4 gap-4">

    <!-- Left Panel: History & Responses -->
    <div class="w-full md:w-1/4 flex flex-col bg-[#1f2937] rounded-lg shadow-lg overflow-hidden">
        <div class="p-4 border-b border-gray-700">
            <div class="flex border-b border-gray-700 -mx-4 px-2">
                <button class="side-tab tab-active py-2 px-4 text-sm font-semibold" data-tab="history">History</button>
                <button class="side-tab tab-inactive py-2 px-4 text-sm font-semibold" data-tab="responses">Responses</button>
            </div>
            <div class="flex justify-end items-center h-8 mt-2">
                 <button id="clear-history" class="text-sm text-gray-400 hover:text-white transition-colors" title="Clear History">
                     <i class="fas fa-trash"></i> Clear History
                 </button>
                 <button id="clear-responses" class="hidden text-sm text-gray-400 hover:text-white transition-colors" title="Clear Responses">
                     <i class="fas fa-trash"></i> Clear Responses
                 </button>
            </div>
        </div>
        <!-- History Panel -->
        <div id="history-panel" class="flex-grow overflow-y-auto p-2">
            <div id="history-list">
                <p class="text-center text-gray-500 mt-4">No requests yet.</p>
            </div>
        </div>
        <!-- Responses Panel -->
        <div id="responses-panel" class="hidden flex-grow overflow-y-auto p-2">
            <div id="responses-list">
                <p class="text-center text-gray-500 mt-4">No responses yet.</p>
            </div>
        </div>
    </div>

    <!-- Main Content (Right Side) -->
    <div class="w-full md:w-3/4 flex flex-col gap-4">
        <!-- Request Panel -->
        <div class="bg-[#1f2937] rounded-lg shadow-lg p-4">
            <h1 class="text-xl font-bold mb-4 text-gray-100">API Request Builder</h1>
            <!-- URL Input and Send Button -->
            <div class="flex gap-2 mb-4">
                <select id="request-method" class="request-input rounded-l-md px-4 py-2 text-white font-semibold">
                    <option>GET</option>
                    <option>POST</option>
                    <option>PUT</option>
                    <option>PATCH</option>
                    <option>DELETE</option>
                </select>
                <input type="text" id="request-url" placeholder="https://api.example.com/data" class="w-full request-input px-4 py-2 text-white">
                <button id="send-request" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-r-md transition-colors flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
            <!-- Request Body/Headers Tabs -->
            <div class="flex border-b border-gray-700 mb-4">
                <button class="request-tab tab-active py-2 px-4" data-tab="body">Body</button>
                <button class="request-tab tab-inactive py-2 px-4" data-tab="headers">Headers</button>
            </div>
            <!-- Request Body Panel -->
            <div id="request-body-panel">
                <textarea id="request-body" class="w-full h-40 request-input rounded-md p-3 text-sm font-mono" placeholder='{ "key": "value" }'></textarea>
            </div>
            <!-- Request Headers Panel -->
            <div id="request-headers-panel" class="hidden">
                <div id="headers-list" class="space-y-2">
                    <!-- Header items will be populated here -->
                </div>
                <button id="add-header" class="mt-4 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md text-sm transition-colors">
                    <i class="fas fa-plus"></i> Add Header
                </button>
            </div>
        </div>

        <!-- Response Panel -->
        <div class="flex-grow bg-[#1f2937] rounded-lg shadow-lg flex flex-col overflow-hidden">
            <div class="p-4 border-b border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-200">Response</h2>
                <div id="response-status" class="flex items-center gap-4 text-sm">
                    <!-- Status, Time, Size will be populated here -->
                </div>
            </div>
            <div id="response-container" class="flex-grow p-4 overflow-auto">
                <div id="loader" class="hidden justify-center items-center h-full">
                    <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-500 h-12 w-12"></div>
                </div>
                <div id="response-content" class="h-full">
                     <p class="text-center text-gray-500">Make a request to see the response.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const methodSelect = document.getElementById('request-method');
        const urlInput = document.getElementById('request-url');
        const sendButton = document.getElementById('send-request');
        const requestBody = document.getElementById('request-body');
        const requestTabs = document.querySelectorAll('.request-tab');
        const requestBodyPanel = document.getElementById('request-body-panel');
        const requestHeadersPanel = document.getElementById('request-headers-panel');
        const headersList = document.getElementById('headers-list');
        const addHeaderBtn = document.getElementById('add-header');
        const responseContainer = document.getElementById('response-container');
        const loader = document.getElementById('loader');
        const responseContent = document.getElementById('response-content');
        const responseStatus = document.getElementById('response-status');
        const sideTabs = document.querySelectorAll('.side-tab');

        const historyPanel = document.getElementById('history-panel');
        const historyList = document.getElementById('history-list');
        const clearHistoryBtn = document.getElementById('clear-history');

        const responsesPanel = document.getElementById('responses-panel');
        const responsesList = document.getElementById('responses-list');
        const clearResponsesBtn = document.getElementById('clear-responses');


        let requestHistory = [];
        let responseLog = [];

        // --- Event Listeners ---
        sendButton.addEventListener('click', handleSendRequest);
        addHeaderBtn.addEventListener('click', () => createHeaderInputRow());
        clearHistoryBtn.addEventListener('click', () => {
            requestHistory = [];
            renderHistory();
        });
        clearResponsesBtn.addEventListener('click', () => {
            responseLog = [];
            renderResponses();
        });

        requestTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                requestTabs.forEach(t => t.classList.replace('tab-active', 'tab-inactive'));
                tab.classList.replace('tab-inactive', 'tab-active');
                const isBody = tab.dataset.tab === 'body';
                requestBodyPanel.classList.toggle('hidden', !isBody);
                requestHeadersPanel.classList.toggle('hidden', isBody);
            });
        });

        sideTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                sideTabs.forEach(t => t.classList.replace('tab-active', 'tab-inactive'));
                tab.classList.replace('tab-inactive', 'tab-active');

                const activeTab = tab.dataset.tab;

                historyPanel.classList.toggle('hidden', activeTab !== 'history');
                responsesPanel.classList.toggle('hidden', activeTab !== 'responses');

                clearHistoryBtn.classList.toggle('hidden', activeTab !== 'history');
                clearResponsesBtn.classList.toggle('hidden', activeTab !== 'responses');
            });
        });

        // --- Functions ---
        async function handleSendRequest() {
            const url = urlInput.value.trim();
            if (!url) {
                // In a real app, use a modal instead of alert.
                alert("Please enter a URL.");
                return;
            }

            const method = methodSelect.value;
            const headers = getHeadersFromInputs();
            const body = requestBody.value;

            loader.style.display = 'flex';
            responseContent.innerHTML = '';
            responseStatus.innerHTML = '';

            const startTime = Date.now();

            try {
                const options = {
                    method,
                    headers: new Headers(headers),
                };

                if (['POST', 'PUT', 'PATCH'].includes(method) && body) {
                    if (!headers['Content-Type']) {
                        options.headers.set('Content-Type', 'application/json');
                    }
                    options.body = body;
                }

                const response = await fetch(url, options);
                const endTime = Date.now();
                const responseData = await response.text();
                const responseSize = new Blob([responseData]).size;

                displayResponse(response, responseData, endTime - startTime, responseSize, method);
                addToHistory({ url, method, headers, body });
            } catch (error) {
                const endTime = Date.now();
                displayError(error, endTime - startTime, url, method);
            } finally {
                loader.style.display = 'none';
            }
        }

        function displayResponse(response, data, time, size, method) {
            const statusClass = response.ok ? 'text-green-400' : 'text-red-400';
            responseStatus.innerHTML = `
                <span class="font-semibold ${statusClass}">Status: ${response.status} ${response.statusText}</span>
                <span class="text-gray-400">Time: ${time} ms</span>
                <span class="text-gray-400">Size: ${(size / 1024).toFixed(2)} KB</span>
            `;

            let formattedBody = `<pre class="text-sm whitespace-pre-wrap word-wrap: break-word;">${data.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</pre>`;
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                try {
                    const jsonData = JSON.parse(data);
                    formattedBody = `<pre class="text-sm whitespace-pre-wrap word-wrap: break-word;">${JSON.stringify(jsonData, null, 2)}</pre>`;
                } catch(e) { /* Fallback to plain text if JSON parsing fails */ }
            }
            responseContent.innerHTML = formattedBody;

            // Log the response
            addToResponses({ url: response.url, method, ok: response.ok, status: response.status, statusText: response.statusText, time, size });
        }

        function displayError(error, time, url, method) {
            responseStatus.innerHTML = `
                <span class="font-semibold text-red-400">Error</span>
                <span class="text-gray-400">Time: ${time} ms</span>
            `;
            responseContent.innerHTML = `<pre class="text-red-400 text-sm">${error.message}</pre>`;

            // Log the error response
            addToResponses({ url, method, ok: false, status: 'Error', statusText: error.message, time, size: 0 });
        }

        function createHeaderInputRow(key = '', value = '') {
            const div = document.createElement('div');
            div.className = 'flex gap-2 items-center';
            div.innerHTML = `
                <input type="text" placeholder="Key" value="${key}" class="w-1/2 request-input rounded-md p-2 text-sm header-key">
                <input type="text" placeholder="Value" value="${value}" class="w-1/2 request-input rounded-md p-2 text-sm header-value">
                <button class="text-red-500 hover:text-red-400 remove-header" title="Remove Header">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
            headersList.appendChild(div);
            div.querySelector('.remove-header').addEventListener('click', () => div.remove());
        }

        function getHeadersFromInputs() {
            const headers = {};
            document.querySelectorAll('#headers-list .flex').forEach(row => {
                const key = row.querySelector('.header-key').value.trim();
                const value = row.querySelector('.header-value').value.trim();
                if (key) headers[key] = value;
            });
            return headers;
        }

        function addToHistory(request) {
            const exists = requestHistory.some(h => h.url === request.url && h.method === request.method && h.body === request.body);
            if(!exists){
                requestHistory.unshift(request);
                if (requestHistory.length > 50) requestHistory.pop();
                renderHistory();
            }
        }

        function renderHistory() {
            if (requestHistory.length === 0) {
                historyList.innerHTML = '<p class="text-center text-gray-500 mt-4">No requests yet.</p>';
                return;
            }
            historyList.innerHTML = '';
            requestHistory.forEach((req, index) => {
                const div = document.createElement('div');
                div.className = 'side-panel-item p-3 rounded-md cursor-pointer transition-colors';
                div.dataset.index = index;
                const methodClass = {
                    GET: 'text-green-400', POST: 'text-yellow-400', PUT: 'text-blue-400',
                    PATCH: 'text-purple-400', DELETE: 'text-red-400',
                }[req.method] || 'text-gray-400';

                div.innerHTML = `
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm ${methodClass}">${req.method}</span>
                    </div>
                    <p class="text-xs text-gray-300 truncate mt-1" title="${req.url}">${req.url}</p>
                `;
                div.addEventListener('click', () => loadFromHistory(index));
                historyList.appendChild(div);
            });
        }

        function loadFromHistory(index) {
            const req = requestHistory[index];
            methodSelect.value = req.method;
            urlInput.value = req.url;
            requestBody.value = req.body || '';
            headersList.innerHTML = '';
            if (req.headers) {
                Object.entries(req.headers).forEach(([key, value]) => createHeaderInputRow(key, value));
            }
             if (Object.keys(req.headers || {}).length === 0) {
                createHeaderInputRow(); // Ensure one empty row if no headers
            }
        }

        function addToResponses(responseInfo) {
            responseLog.unshift(responseInfo);
            if (responseLog.length > 50) responseLog.pop();
            renderResponses();
        }

        function renderResponses() {
            if (responseLog.length === 0) {
                responsesList.innerHTML = '<p class="text-center text-gray-500 mt-4">No responses yet.</p>';
                return;
            }
            responsesList.innerHTML = '';
            responseLog.forEach((res) => {
                const div = document.createElement('div');
                div.className = 'side-panel-item p-3 rounded-md';
                const statusClass = res.ok ? 'text-green-400' : 'text-red-400';
                const methodClass = {
                    GET: 'text-green-400', POST: 'text-yellow-400', PUT: 'text-blue-400',
                    PATCH: 'text-purple-400', DELETE: 'text-red-400',
                }[res.method] || 'text-gray-400';

                div.innerHTML = `
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm ${methodClass}">${res.method}</span>
                        <span class="font-semibold text-xs ${statusClass}">${res.status} ${res.statusText}</span>
                    </div>
                    <p class="text-xs text-gray-300 truncate mt-1" title="${res.url}">${res.url}</p>
                    <div class="text-xs text-gray-500 mt-1 flex justify-between">
                        <span>${res.time} ms</span>
                        <span>${(res.size / 1024).toFixed(2)} KB</span>
                    </div>
                `;
                responsesList.appendChild(div);
            });
        }

        // --- Initial Setup ---
        createHeaderInputRow();
        renderHistory();
        renderResponses();
    </script>
</body>
</html>
