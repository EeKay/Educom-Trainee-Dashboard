import '../../css/chatbot.css';
import eddieAvatar from '../../img/eddie-avatar.svg';
import arrowUpCircle from '../../img/Arrow up-circle.svg';
import { useState, useEffect, useRef } from 'react';

const DEFAULT_MESSAGE = "Hello! I am Eddie the chatbot, do you have a question?";

export default function ChatBot() {
    const [chatopen, setChatopen] = useState(false);
    const [minimized, setMinimized] = useState(false);
    const [messages, setMessages] = useState([]);
    const [inputValue, setInputValue] = useState('');
    const bottomRef = useRef(null);

    const handlePopClick = () => {
        if (minimized) {
            setMinimized(false);
        } else {
            setChatopen(!chatopen);
        }
    };

    const minimizeChat = () => setMinimized(true);

    const deleteChat = () => {
        setMessages([]);
        setMinimized(false);
        setChatopen(false);
    };

    function getCsrfToken() {
        const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
        return match ? decodeURIComponent(match[1]) : null;
    }

    useEffect(() => {
        if (chatopen && messages.length === 0) {
            const timer = setTimeout(() => {
                setMessages([{ sender: 'bot', type: 'text', text: DEFAULT_MESSAGE }]);
            }, 1200);
            return () => clearTimeout(timer);
        }
    }, [chatopen, messages.length]);

    useEffect(() => {
        bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, [messages]);

    // Sends the question to the ViewController, which makes a connection to the /nan workflow
    function askEddie(question, faqRejected = false) {
        fetch('/nan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            },
            credentials: 'include', // needed so the cookie is actually sent
            body: JSON.stringify({ question, faqRejected }),
        })
            .then((res) => res.json())
            .then((data) => {
                setMessages((prev) => [
                    ...prev,
                    { sender: 'bot', type: 'text', text: data[0].answer ?? "Sorry, I couldn't find an answer to that.", resolved: false },
                ]);
                console.log(data);
                console.log(data.answer);
            })
            .catch((err) => {
                console.error('Error fetching chat response:', err);
                setMessages((prev) => [
                    ...prev,
                    { sender: 'bot', type: 'text', text: "Something went wrong, please try again.", resolved: false },
                ]);
            });
    }

    function sendMessage() {
        const question = inputValue.trim();
        if (!question) return;

        setMessages((prev) => [...prev, { sender: 'user', type: 'text', text: question }]);
        setInputValue('');
        askEddie(question, false);
    }


    // Fire-and-forget: just reports faqRejected, doesn't render another answer bubble
    function askEddieFeedbackOnly(question, faqRejected) {
        fetch('/nan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-XSRF-TOKEN': getCsrfToken(),
            },
            credentials: 'include', // needed so the cookie is actually sent
            body: JSON.stringify({ question, faqRejected }),
        }).catch((err) => console.error('Error sending feedback:', err));
    }

    const hasSavedChat = minimized && messages.length > 0;

    return (
        <div id="chatCon">
            <div className={`chat-box ${chatopen && !minimized ? 'open' : ''}`}>
                <div className="header">
                    <img
                        className="header-avatar"
                        src={eddieAvatar}
                        alt=""
                        onClick={minimizeChat}
                        style={{ cursor: 'pointer' }}
                    />
                    <span className="header-name">Eddie</span>
                    <div className="header-controls">
                        <button className="minimize" onClick={minimizeChat}>&minus;</button>
                        <button className="delete" onClick={deleteChat}>&times;</button>
                    </div>
                </div>
                <div className="bot-intro">
                    <img className="bot-avatar" src={eddieAvatar} alt="Eddie the chatbot"/>
                    <span className="bot-name">Eddie</span>
                </div>
                <div className="msg-area">
                    {messages.map((msg, i) => {
                        return (
                            <p className={msg.sender === 'user' ? 'right' : 'left'} key={i}>
                                <span>{msg.text}</span>
                            </p>
                        );
                    })}
                </div>
                <div className="footer">
                    <input
                        type="text"
                        placeholder="Message..."
                        value={inputValue}
                        onChange={(e) => setInputValue(e.target.value)}
                        onKeyDown={(e) => e.key === 'Enter' && sendMessage()}
                    />
                    <button className="send-btn" onClick={sendMessage}>
                        <img src={arrowUpCircle} alt="Send" />
                    </button>
                </div>
            </div>
            <div className="pop">
                <img onClick={handlePopClick} src={eddieAvatar} alt="Open chat" />
                {hasSavedChat && <span className="saved-chat-dot" />}
            </div>
        </div>
    );
}