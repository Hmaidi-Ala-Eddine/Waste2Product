// ============================================
// PREMIUM AI CHATBOT SCRIPT - ELITE EDITION
// ============================================

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        API_BASE: '/chatbot',
        MAX_RETRIES: 3,
        RETRY_DELAY: 2000,
        TYPING_DELAY: 500,
        MAX_CHARS: 1000,
        AUTO_SCROLL_DELAY: 100,
        NAVIGATION_LINKS: {
            'submit.*request|waste.*request|submit.*waste': {
                url: '/waste-requests',
                text: 'Submit Request',
                icon: '📋'
            },
            'collector|become.*collector|collector.*application': {
                url: '/collector-application',
                text: 'Become Collector',
                icon: '🚚'
            },
            'shop|marketplace|product|buy': {
                url: '/shop',
                text: 'Browse Shop',
                icon: '🛒'
            },
            'cart|shopping.*cart': {
                url: '/cart',
                text: 'View Cart',
                icon: '🛍️'
            },
            'order|my.*order': {
                url: '/my-orders',
                text: 'My Orders',
                icon: '📦'
            }
        }
    };

    // State
    let state = {
        isOpen: false,
        isProcessing: false,
        messageCount: 0,
        retryCount: 0,
        unreadCount: 0,
        soundEnabled: true,
    };

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', init);

    function init() {
        // Get DOM Elements
        const elements = {
            toggleBtn: document.getElementById('chatToggleBtn'),
            chatWindow: document.getElementById('chatWindow'),
            messagesContainer: document.getElementById('messagesContainer'),
            chatForm: document.getElementById('chatForm'),
            chatInput: document.getElementById('chatInput'),
            sendBtn: document.getElementById('sendBtn'),
            clearBtn: document.getElementById('clearChatBtn'),
            minimizeBtn: document.getElementById('minimizeBtn'),
            typingIndicator: document.getElementById('typingIndicator'),
            charCount: document.getElementById('charCount'),
        };

        // Check if elements exist
        if (!elements.toggleBtn) {
            return;
        }

        // Get CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Event Listeners
        elements.toggleBtn.addEventListener('click', () => toggleChat(elements));
        elements.minimizeBtn?.addEventListener('click', () => closeChat(elements));
        elements.clearBtn?.addEventListener('click', () => clearChat(elements));
        elements.chatForm.addEventListener('submit', (e) => handleSubmit(e, elements, csrfToken));
        elements.chatInput.addEventListener('input', () => handleInput(elements));
        elements.chatInput.addEventListener('keydown', (e) => handleKeyDown(e, elements, csrfToken));

        // Quick Reply Buttons
        document.querySelectorAll('.quick-reply-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const text = btn.getAttribute('data-text');
                if (text) {
                    elements.chatInput.value = text;
                    elements.chatInput.focus();
                    handleInput(elements);
                }
            });
        });

        // Auto-resize textarea
        elements.chatInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    function toggleChat(elements) {
        state.isOpen = !state.isOpen;
        elements.toggleBtn.classList.toggle('active');
        elements.chatWindow.classList.toggle('active');
        
        if (state.isOpen) {
            elements.chatInput.focus();
            scrollToBottom(elements);
            // Reset unread count
            state.unreadCount = 0;
            updateBadge(elements);
        }
    }

    function updateBadge(elements) {
        const badge = elements.toggleBtn.querySelector('.chat-badge');
        if (state.unreadCount > 0 && !state.isOpen) {
            badge.textContent = state.unreadCount;
            badge.classList.add('has-unread');
        } else {
            badge.textContent = 'AI';
            badge.classList.remove('has-unread');
        }
    }

    function closeChat(elements) {
        state.isOpen = false;
        elements.toggleBtn.classList.remove('active');
        elements.chatWindow.classList.remove('active');
    }

    async function clearChat(elements) {
        if (!confirm('Are you sure you want to clear the chat history?')) {
            return;
        }

        try {
            const response = await fetch(CONFIG.API_BASE + '/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });

            if (response.ok) {
                // Remove all messages except welcome message
                const messages = elements.messagesContainer.querySelectorAll('.message:not(.welcome-message)');
                messages.forEach(msg => msg.remove());
                
                state.messageCount = 0;
                showToast('Chat cleared successfully', 'success');
            }
        } catch (error) {
            showToast('Failed to clear chat', 'error');
        }
    }

    function handleInput(elements) {
        const length = elements.chatInput.value.length;
        elements.charCount.textContent = length;
        
        if (length > CONFIG.MAX_CHARS) {
            elements.chatInput.value = elements.chatInput.value.substring(0, CONFIG.MAX_CHARS);
            elements.charCount.textContent = CONFIG.MAX_CHARS;
        }
    }

    function handleKeyDown(e, elements, csrfToken) {
        // Enter without Shift = Send
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSubmit(e, elements, csrfToken);
        }
        // Ctrl+Enter = Also send (alternative)
        else if (e.key === 'Enter' && e.ctrlKey) {
            e.preventDefault();
            handleSubmit(e, elements, csrfToken);
        }
    }

    async function handleSubmit(e, elements, csrfToken) {
        e.preventDefault();
        
        const message = elements.chatInput.value.trim();
        
        if (!message || state.isProcessing) {
            return;
        }

        // Add user message
        addMessage(message, 'user', elements);
        elements.chatInput.value = '';
        elements.chatInput.style.height = 'auto';
        handleInput(elements);
        
        // Show typing indicator
        showTyping(elements);
        
        // Send to API
        await sendMessage(message, elements, csrfToken);
    }

    async function sendMessage(message, elements, csrfToken, retryCount = 0) {
        state.isProcessing = true;
        elements.sendBtn.disabled = true;
        elements.sendBtn.classList.add('loading');

        try {
            const response = await fetch(CONFIG.API_BASE + '/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: message }),
            });
            
            const data = await response.json();

            hideTyping(elements);

            if (data.success && data.response) {
                addMessage(data.response, 'bot', elements, data.timestamp);
                state.retryCount = 0;
            } else {
                throw new Error(data.message || 'Unknown error');
            }

        } catch (error) {
            hideTyping(elements);
            
            if (retryCount < CONFIG.MAX_RETRIES) {
                setTimeout(() => {
                    sendMessage(message, elements, csrfToken, retryCount + 1);
                }, CONFIG.RETRY_DELAY);
            } else {
                addMessage(
                    '😔 I apologize, but I\'m having trouble connecting right now. Please try again in a moment.',
                    'bot',
                    elements,
                    null,
                    true
                );
            }
        } finally {
            state.isProcessing = false;
            elements.sendBtn.disabled = false;
            elements.sendBtn.classList.remove('loading');
        }
    }

    function addMessage(text, sender, elements, timestamp = null, isError = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        if (isError) messageDiv.classList.add('error-message');

        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        
        if (sender === 'bot') {
            avatar.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <rect x="6" y="4" width="12" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="9" cy="9" r="1" fill="currentColor"/>
                <circle cx="15" cy="9" r="1" fill="currentColor"/>
                <path d="M9 13h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>`;
        } else {
            avatar.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>`;
        }

        const content = document.createElement('div');
        content.className = 'message-content';

        const bubble = document.createElement('div');
        bubble.className = 'message-bubble';

        const textDiv = document.createElement('div');
        textDiv.className = 'message-text';
        textDiv.innerHTML = formatMessage(text);

        bubble.appendChild(textDiv);

        const meta = document.createElement('div');
        meta.className = 'message-meta';

        const time = document.createElement('span');
        time.className = 'message-time';
        time.textContent = timestamp || 'Just now';

        meta.appendChild(time);

        content.appendChild(bubble);
        content.appendChild(meta);

        // Add action buttons for bot messages
        if (sender === 'bot' && !isError) {
            const actions = document.createElement('div');
            actions.className = 'message-actions';
            
            // Copy button
            const copyBtn = document.createElement('button');
            copyBtn.className = 'action-btn copy-btn';
            copyBtn.innerHTML = `
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                    <path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1" stroke="currentColor" stroke-width="2"/>
                </svg>
                Copy
            `;
            copyBtn.onclick = () => copyMessage(text, copyBtn);
            actions.appendChild(copyBtn);
            
            // Detect and add navigation buttons
            const navButtons = detectNavigationLinks(text);
            navButtons.forEach(navBtn => actions.appendChild(navBtn));
            
            content.appendChild(actions);
        }

        messageDiv.appendChild(avatar);
        messageDiv.appendChild(content);

        elements.messagesContainer.appendChild(messageDiv);

        state.messageCount++;
        
        // Update unread count if chat is closed
        if (sender === 'bot' && !state.isOpen) {
            state.unreadCount++;
            updateBadge(elements);
            playNotificationSound();
        }
        
        scrollToBottom(elements);
    }

    function detectNavigationLinks(text) {
        const buttons = [];
        const textLower = text.toLowerCase();
        const addedUrls = new Set(); // Prevent duplicate buttons
        
        // Check each pattern in config
        Object.entries(CONFIG.NAVIGATION_LINKS).forEach(([pattern, link]) => {
            const regex = new RegExp(pattern, 'i');
            
            // If text matches pattern and we haven't added this URL yet
            if (regex.test(textLower) && !addedUrls.has(link.url)) {
                const navBtn = document.createElement('button');
                navBtn.className = 'action-btn nav-btn';
                navBtn.innerHTML = `${link.icon} ${link.text} →`;
                navBtn.onclick = () => {
                    window.location.href = link.url;
                };
                
                buttons.push(navBtn);
                addedUrls.add(link.url);
            }
        });
        
        return buttons;
    }

    function copyMessage(text, button) {
        // Remove HTML tags for plain text
        const plainText = text.replace(/<[^>]*>/g, '').replace(/\*\*/g, '');
        
        navigator.clipboard.writeText(plainText).then(() => {
            const originalHTML = button.innerHTML;
            button.innerHTML = '✓ Copied!';
            button.classList.add('copied');
            
            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('copied');
            }, 2000);
        }).catch(err => {
            showToast('Failed to copy', 'error');
        });
    }

    function playNotificationSound() {
        if (!state.soundEnabled) return;
        
        try {
            // Create a pleasant two-tone notification sound
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            
            // First tone (higher pitch)
            const osc1 = audioContext.createOscillator();
            const gain1 = audioContext.createGain();
            
            osc1.connect(gain1);
            gain1.connect(audioContext.destination);
            
            osc1.frequency.value = 1000; // C6 note
            osc1.type = 'sine';
            
            gain1.gain.setValueAtTime(0.15, audioContext.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.15);
            
            osc1.start(audioContext.currentTime);
            osc1.stop(audioContext.currentTime + 0.15);
            
            // Second tone (lower pitch, slightly delayed)
            setTimeout(() => {
                const osc2 = audioContext.createOscillator();
                const gain2 = audioContext.createGain();
                
                osc2.connect(gain2);
                gain2.connect(audioContext.destination);
                
                osc2.frequency.value = 800; // G5 note
                osc2.type = 'sine';
                
                gain2.gain.setValueAtTime(0.15, audioContext.currentTime);
                gain2.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
                
                osc2.start(audioContext.currentTime);
                osc2.stop(audioContext.currentTime + 0.2);
            }, 80);
            
        } catch (error) {
            // Silently fail if audio not supported
        }
    }

    function formatMessage(text) {
        // Convert **bold** to <strong>
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Convert *italic* to <em>
        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        // Convert line breaks
        text = text.replace(/\n/g, '<br>');
        
        // Convert URLs to links
        text = text.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener">$1</a>');
        
        // Wrap in paragraph
        return '<p>' + text + '</p>';
    }

    function showTyping(elements) {
        elements.typingIndicator.classList.add('active');
        scrollToBottom(elements);
    }

    function hideTyping(elements) {
        elements.typingIndicator.classList.remove('active');
    }

    function scrollToBottom(elements) {
        setTimeout(() => {
            elements.messagesContainer.scrollTop = elements.messagesContainer.scrollHeight;
        }, CONFIG.AUTO_SCROLL_DELAY);
    }

    function showToast(message, type = 'info') {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            bottom: 100px;
            right: 24px;
            background: ${type === 'success' ? '#48bb78' : '#f56565'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 999999;
            animation: slideIn 0.3s ease;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

})();
