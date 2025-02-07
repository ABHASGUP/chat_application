const form = document.querySelector(".typing-area");
if(form) { // Check if form exists before accessing its elements
    const incoming_id = form.querySelector(".incoming_id").value;
    const inputField = form.querySelector(".input-field");
    const sendBtn = form.querySelector("button[type='submit']");
    const chatBox = document.querySelector(".chat-box");
    const emojiBtn = document.querySelector(".emoji-btn");
    const attachmentBtn = document.querySelector(".attachment-btn");
    const emojiWrapper = document.querySelector(".emoji-wrapper");
    const attachmentMenu = document.querySelector(".attachment-menu");
    const fileInput = document.querySelector("#file-input");

    let previousHTML = '';
    let isScrolledToBottom = true;

    // Emoji picker functionality
    const emojiPicker = document.querySelector(".emoji-picker");
    const emojiCategories = document.querySelectorAll(".emoji-categories span");
    const emojiList = document.querySelector(".emoji-list");

    // Emoji data by category
    const emojiData = {
        smileys: ["😀", "😃", "😄", "😁", "😅", "😂", "🤣", "😊", "😇", "🙂", "🙃", "😉", "😌", "😍", "🥰", "😘"],
        people: ["👋", "🤚", "✋", "🖐️", "👌", "🤏", "✌️", "🤞", "🤟", "🤘", "🤙", "👈", "👉", "👆", "🖕", "👇"],
        animals: ["🐶", "🐱", "🐭", "🐹", "🐰", "🦊", "🐻", "🐼", "🐨", "🐯", "🦁", "🐮", "🐷", "🐸", "🐵", "🐔"],
        food: ["🍎", "🍐", "🍊", "🍋", "🍌", "🍉", "🍇", "🍓", "🍈", "🍒", "🍑", "🥭", "🍍", "🥥", "🥝", "🍅"],
        activities: ["⚽", "🏀", "🏈", "⚾", "🥎", "🎾", "🏐", "🏉", "🥏", "🎱", "🪀", "🏓", "🏸", "🏒", "🏑", "🥍"],
        places: ["🚗", "🚕", "🚙", "🚌", "🚎", "🏎️", "🚓", "🚑", "🚒", "🚐", "🚚", "🚛", "🚜", "🛴", "🚲", "🛵"],
        symbols: ["❤️", "🧡", "💛", "💚", "💙", "💜", "🖤", "🤍", "🤎", "💔", "❣️", "💕", "💞", "💓", "💗", "💖"]
    };

    // Toggle emoji picker
    emojiBtn.addEventListener("click", () => {
        emojiPicker.classList.toggle("active");
        if (emojiPicker.classList.contains("active") && !emojiList.children.length) {
            loadEmojis("smileys"); // Load default category
        }
    });

    // Close emoji picker when clicking outside
    document.addEventListener("click", (e) => {
        if (!emojiBtn.contains(e.target) && !emojiPicker.contains(e.target)) {
            emojiPicker.classList.remove("active");
        }
    });

    // Handle emoji category selection
    emojiCategories.forEach(category => {
        category.addEventListener("click", () => {
            // Remove active class from all categories
            emojiCategories.forEach(cat => cat.classList.remove("active"));
            // Add active class to clicked category
            category.classList.add("active");
            // Load emojis for selected category
            loadEmojis(category.dataset.category);
        });
    });

    // Load emojis for a category
    function loadEmojis(category) {
        emojiList.innerHTML = "";
        emojiData[category].forEach(emoji => {
            const span = document.createElement("span");
            span.textContent = emoji;
            span.addEventListener("click", () => {
                insertEmoji(emoji);
            });
            emojiList.appendChild(span);
        });
    }

    // Insert emoji at cursor position
    function insertEmoji(emoji) {
        const cursorPos = inputField.selectionStart;
        const textBefore = inputField.value.substring(0, cursorPos);
        const textAfter = inputField.value.substring(cursorPos);
        inputField.value = textBefore + emoji + textAfter;
        inputField.focus();
        inputField.selectionStart = cursorPos + emoji.length;
        inputField.selectionEnd = cursorPos + emoji.length;
    }

    // Handle attachment menu
    attachmentBtn.onclick = () => {
        attachmentMenu.classList.toggle('active');
        emojiPicker.classList.remove('active');
    };

    // Handle file uploads
    document.querySelectorAll('.upload-btn').forEach(button => {
        button.onclick = () => {
            const type = button.dataset.type;
            if (type === 'location') {
                // Handle location sharing
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        const location = `📍 Location: ${position.coords.latitude},${position.coords.longitude}`;
                        inputField.value = location;
                        attachmentMenu.classList.remove('active');
                    });
                }
            } else {
                fileInput.accept = type === 'photo' ? 'image/*' : '.pdf,.doc,.docx,.txt';
                fileInput.click();
            }
        };
    });

    // Handle file selection
    fileInput.onchange = () => {
        const file = fileInput.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('incoming_id', incoming_id);
            
            fetch('php/upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    attachmentMenu.classList.remove('active');
                    fileInput.value = '';
                    fetchMessages();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    };

    form.onsubmit = (e) => {
        e.preventDefault();
    }

    inputField.oninput = () => {
        if(inputField.value.trim() !== "") {
            sendBtn.classList.add("active");
        } else {
            sendBtn.classList.remove("active");
        }
    }

    chatBox.onscroll = () => {
        isScrolledToBottom = Math.abs(chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight) < 1;
    }

    function scrollToBottom() {
        if(isScrolledToBottom) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    }

    sendBtn.onclick = () => {
        if(inputField.value.trim() === "") return;

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/insert-chat.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                inputField.value = "";
                sendBtn.classList.remove("active");
                fetchMessages();
            }
        }
        let formData = new FormData(form);
        xhr.send(formData);
    }

    function fetchMessages() {
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "php/get-chat.php", true);
        xhr.onload = () => {
            if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                let newHTML = xhr.response;
                if(newHTML !== previousHTML) {
                    chatBox.innerHTML = newHTML;
                    previousHTML = newHTML;
                    scrollToBottom();
                    // Convert emoji characters to Twemoji graphics
                    twemoji.parse(chatBox);
                }
            }
        }
        let formData = new FormData(form);
        xhr.send(formData);
    }

    // Initial setup
    fetchMessages();

    // Update messages every 2.5 seconds
    setInterval(fetchMessages, 2500);
}
