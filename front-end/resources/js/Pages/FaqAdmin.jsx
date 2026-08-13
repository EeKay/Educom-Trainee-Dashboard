import {useState} from 'react';
import Navbar from '../Components/NavbarAdmin';
import SearchBar from '../Components/SearchBar';
import FaqItemAdmin from '../Components/FaqItemAdmin';
import ChatBot from '../Components/ChatBot';
import '../../css/faq.css';

export default function FaqAdmin(props){
    const [searchPrompt, setSearchPrompt] = useState('');
    const [faqs, setFaqs] = useState(props.faqs);

    const filteredFaqs = faqs.filter((faq) =>
        faq.question.toLowerCase().includes(searchPrompt.toLowerCase())
    );

    const handleToggled = (id, nowActive) => {
        setFaqs((prev) =>
            prev.map((faq) => faq.id === id ? { ...faq, is_active: nowActive } : faq)
        );
    };

    const handleDeleted = (id) => {
        setFaqs((prev) => prev.filter((faq) => faq.id !== id));
    };

    return(
        <div>
            <Navbar />
            <div className="faq-container">
                <div className="faq-heading">
                    <div className="faq-eyebrow">FAQs</div>
                    <h1 className="faq-title">Frequently Asked Questions</h1>
                </div>

                <SearchBar
                    searchPrompt={searchPrompt}
                    onSearchChange={setSearchPrompt}
                    faqs={faqs}
                />

                <h2 className="faq-subheading">Top FAQ's</h2>

                {filteredFaqs.length === 0 ? (
                    <div className="database-empty">No matching questions found.</div>
                ) : (
                    <div className="database">
                        {filteredFaqs.map((faq) => (
                            <FaqItemAdmin
                                key={faq.id}
                                id={faq.id}
                                question={faq.question}
                                answer={faq.answer}
                                isActive={faq.is_active}
                                onToggled={handleToggled}
                                onDeleted={handleDeleted}
                            />
                        ))}
                    </div>
                )}
                <ChatBot/>
            </div>
        </div>
    )
}