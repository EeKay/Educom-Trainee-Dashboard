import {useState} from 'react';
import Navbar from '../Components/NavbarAdmin';
import SearchBar from '../Components/SearchBar';
import FaqItemAdmin from '../Components/FaqItemAdmin';
import AddQuestion from '../Components/AddQuestion';
import ChatBot from '../Components/ChatBot';
import '../../css/faq.css';

/* TO ADD

Add update question function

Max questions shown per page

*/

export default function FaqAdmin(props){
    const [searchPrompt, setSearchPrompt] = useState('');
    const [faqs, setFaqs] = useState(Array.isArray(props.faqs) ? props.faqs : []);

    const list = Array.isArray(faqs) ? faqs : [];
    const filteredFaqs = list.filter((faq) =>
        (faq.question ?? '')
            .toLowerCase()
            .includes(searchPrompt.toLowerCase())
    );

    const handleToggled = (id, nowActive) => {
        setFaqs((prev) =>
            prev.map((faq) => faq.id === id ? { ...faq, is_active: nowActive } : faq)
        );
    };

    const handleDeleted = (id) => {
        setFaqs((prev) => prev.filter((faq) => faq.id !== id));
    };

    const handleAdded = (newFaq) => {
        const faq = newFaq?.data ?? newFaq;

        if (!faq?.question) {
            console.error('Invalid FAQ returned from API:', faq);
            return;
        }

        setFaqs((prev) => [...prev, faq]);
        
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

                <AddQuestion
                    onSubmitted = {handleAdded}
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