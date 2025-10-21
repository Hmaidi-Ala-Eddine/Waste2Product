<!-- Premium AI Chatbot Widget - Elite Edition -->
<link rel="stylesheet" href="{{ asset('assets/chatbot-elite.css') }}">

<div id="aiChatbotPremium" class="ai-chatbot-elite">
    
    <!-- Floating Chat Button -->
    <button id="chatToggleBtn" class="chat-toggle-button" aria-label="Toggle AI Assistant">
        <div class="button-content">
            <svg class="icon-chat" width="32" height="32" viewBox="0 0 24 24" fill="none">
                <g filter="url(#glow)">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white" fill-opacity="0.9"/>
                    <circle cx="8" cy="10" r="1.2" fill="#667eea"/>
                    <circle cx="16" cy="10" r="1.2" fill="#667eea"/>
                    <path d="M8 13c0 2 1.8 3.5 4 3.5s4-1.5 4-3.5" stroke="#667eea" stroke-width="1.5" stroke-linecap="round"/>
                </g>
                <defs>
                    <filter id="glow" x="-2" y="-2" width="28" height="28" filterUnits="userSpaceOnUse">
                        <feGaussianBlur stdDeviation="0.5" result="blur"/>
                        <feFlood flood-color="white" flood-opacity="0.3"/>
                        <feComposite in2="blur" operator="in"/>
                        <feMerge>
                            <feMergeNode/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
            </svg>
            <svg class="icon-close" width="28" height="28" viewBox="0 0 24 24" fill="none">
                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <span class="chat-badge">AI</span>
        <span class="pulse-ring"></span>
    </button>

    <!-- Chat Window -->
    <div id="chatWindow" class="chat-window">
        
        <!-- Header -->
        <div class="chat-header">
            <div class="header-left">
                <div class="bot-avatar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect x="6" y="4" width="12" height="14" rx="2" stroke="white" stroke-width="2"/>
                        <circle cx="9" cy="9" r="1" fill="white"/>
                        <circle cx="15" cy="9" r="1" fill="white"/>
                        <path d="M9 13h6" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <rect x="5" y="2" width="2" height="2" rx="1" fill="white"/>
                        <rect x="17" y="2" width="2" height="2" rx="1" fill="white"/>
                        <rect x="8" y="18" width="3" height="4" rx="1" fill="white"/>
                        <rect x="13" y="18" width="3" height="4" rx="1" fill="white"/>
                    </svg>
                </div>
                <div class="bot-info">
                    <h3>AI Assistant</h3>
                    <p class="status"><span class="status-indicator"></span> Online & Ready</p>
                </div>
            </div>
            <div class="header-actions">
                <button id="clearChatBtn" class="header-btn" title="Clear chat" aria-label="Clear chat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button id="minimizeBtn" class="header-btn" title="Minimize" aria-label="Minimize chat">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="messagesContainer" class="messages-container">
            <!-- Welcome Message -->
            <div class="message bot-message welcome-message">
                <div class="message-avatar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <rect x="6" y="4" width="12" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                        <circle cx="9" cy="9" r="1" fill="currentColor"/>
                        <circle cx="15" cy="9" r="1" fill="currentColor"/>
                        <path d="M9 13h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <div class="message-text">
                            <p><strong>👋 Welcome to Waste2Product!</strong></p>
                            <p>I'm your AI assistant, powered by Groq's advanced AI. I can help you with:</p>
                            <ul class="feature-list">
                                <li>♻️ Submitting waste collection requests</li>
                                <li>🚚 Becoming a verified collector</li>
                                <li>📍 Understanding coverage & waste types</li>
                                <li>🛒 Using the product marketplace</li>
                                <li>⭐ Rating & platform navigation</li>
                            </ul>
                            <p class="cta-text">Ask me anything about waste management! 🌱</p>
                        </div>
                    </div>
                    <div class="message-meta">
                        <span class="message-time">Just now</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Replies -->
        <div id="quickRepliesContainer" class="quick-replies-container">
            <div class="quick-replies-scroll">
                <button class="quick-reply-btn" data-text="How do I submit a waste request?">
                    <span class="qr-icon">📦</span>
                    <span class="qr-text">Submit Request</span>
                </button>
                <button class="quick-reply-btn" data-text="How to become a collector?">
                    <span class="qr-icon">🚚</span>
                    <span class="qr-text">Become Collector</span>
                </button>
                <button class="quick-reply-btn" data-text="What areas do you cover?">
                    <span class="qr-icon">📍</span>
                    <span class="qr-text">Coverage</span>
                </button>
                <button class="quick-reply-btn" data-text="What waste types are accepted?">
                    <span class="qr-icon">♻️</span>
                    <span class="qr-text">Waste Types</span>
                </button>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="typingIndicator" class="typing-indicator">
            <div class="message-avatar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                    <rect x="6" y="4" width="12" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                    <circle cx="9" cy="9" r="1" fill="currentColor"/>
                    <circle cx="15" cy="9" r="1" fill="currentColor"/>
                    <path d="M9 13h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Input Area -->
        <div class="input-container">
            <form id="chatForm" class="chat-form">
                <textarea 
                    id="chatInput" 
                    class="chat-input" 
                    placeholder="Type your message..."
                    rows="1"
                    maxlength="1000"
                    aria-label="Chat message"
                ></textarea>
                <button type="submit" id="sendBtn" class="send-button" aria-label="Send message">
                    <svg class="icon-send" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="loading-spinner"></span>
                </button>
            </form>
            <div class="input-footer">
                <span class="char-count"><span id="charCount">0</span>/1000</span>
                <span class="powered-by">Powered by <strong>Groq AI</strong></span>
            </div>
        </div>

    </div>

</div>

<script src="{{ asset('assets/chatbot-elite.js') }}"></script>
