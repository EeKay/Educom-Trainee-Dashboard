import '../../css/chatbot.css';
import eddieAvatar from '../../img/eddie-avatar.svg';
import arrowUpCircle from '../../img/Arrow up-circle.svg';
import { useState, useEffect } from 'react';
import { setLayoutProps } from '@inertiajs/react';

const DEFAULT_MESSAGE = "Hello! I am Eddie the chatbot, do you have a question?";

export default function ChatBot({ message = [] }) {

    const [chatopen, setChatopen] = useState(false);
    const [minimized, setMinimized] = useState(false);
    const [messages, setMessages] = useState([]);
    const [isTyping, setIsTyping] = useState(false);
    const [question, setQuestion] = useState('');
    const [faqRejected, setFaqRejected] = useState (false);

    const handlePopClick = () => {
        if (minimized) {
            setMinimized(false);
        } else {
            setChatopen(!chatopen);
        }
    };

    const minimizeChat = () => {
        setMinimized(true);
    };

    const deleteChat = () => {
        setMessages([]);
        setIsTyping(false);
        setMinimized(false);
        setChatopen(false);
    };

    // useEffect(() =>{
    //     fetch(`${API_BASE}/nan`, {
    //         method: 'POST',
    //         headers:{
    //             "Content-Type": "application/json",
    //             "accept" : "application/json",
    //             "Access-Control-Allow-Origin": "*",
    //             "Access-Control-Allow-Methods": "POST",
    //             "Authorization": "Bearer 463|H7ztAS1gkOUb4V5CYjHhTVtSFNSIbPyWDtvRxJjm549eebc2"//hardcoded for now
    //         },
    //         // credentials: "include",
    //         body: JSON.stringify({
    //             question: "question", 
    //             faqRejected: "false",
    //         })
    //     })
        
    //     .then(response => response.text())   
    //     .then(data => {
    //         console.log(data);
    //     })
    //     .catch(error => console.error ("error: ", error));

    // }, []);

    useEffect(() => {
        if (chatopen && messages.length === 0) {
            setIsTyping(true);

            const timer = setTimeout(() => {
                setIsTyping(false);
                setMessages([DEFAULT_MESSAGE]);
            }, 1200);

            return () => clearTimeout(timer);
        }
    }, [chatopen, messages.length]);

    useEffect(() => {
        if (message.length > 0) {
            setMessages((prev) => [prev[0], ...message]);
        }
    }, [message]);

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
                    <img className="bot-avatar" src={eddieAvatar} alt="Eddie the chatbot" />
                    <span className="bot-name">Eddie</span>
                </div>

                <div className="msg-area">
                    {messages.map((msg, i) => (
                        i % 2 ? (
                            <p className="right" key={i}><span>{msg}</span></p>
                        ) : (
                            <p className="left" key={i}><span>{msg}</span></p>
                        )
                    ))}

                    {isTyping && (
                        <p className="left">
                            <span className="typing-indicator">
                                <i></i><i></i><i></i>
                            </span>
                        </p>
                    )}
                </div>

                <div className="footer">
                    <input type="text" placeholder="Message..." />
                    <button className="send-btn">
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
//antwoord krijg je ook terug