import {useState} from 'react';
import '../../css/faq-item.css';

export default function FaqItem({question, answer}){
    const [isOpen, setIsOpen] = useState(false);


    return(
        <div className = "faq-item">
            <button className = "faq-item-header" onClick = {() => setIsOpen(!isOpen)}>
                <span className = "faq-item-question">{question}</span>
                <span className = "faq-item-icon">{isOpen ? '-' : '+'}</span>
            </button>

            <div className={`faq-item-answer ${isOpen ? 'faq-item-answer-open' : ''}`}>
                <div className = "faq-item-answer-inner">{answer}</div>
            </div>
        </div>
    )
}
