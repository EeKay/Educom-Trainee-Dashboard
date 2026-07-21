import {useState} from 'react';
import Navbar from '../Components/Navbar';
import SearchBar from '../Components/SearchBar';
import FaqItem from '../Components/FaqItem';
import '../../css/faq.css';

export default function Faq({faqEntries}){
    const [searchPrompt, setSearchPrompt] = useState('');

    //placeholder
    const entries = faqEntries || [
            {
                id: 1,
                question: "How do I log into my portal?",
                answer: "Go to https://portal.educom.nu in your browser. You'll see a login screen with two fields: your username (your Educom email address) and your password. You'll automatically receive your login credentials via email once your instructor has onboarded you.",
                },
                {
                id: 2,
                question: "What should I do if my password doesn't work?",
                answer: "Use the “forgot password” link on the login page, or contact your instructor.",
                },
                {
                id: 3,
                question: "I was just onboarded but don't see any data—what should I do?",
                answer: "It can take up to 24 hours for your first usage data to appear. Please contact us if it takes longer.",
                },
                {
                id: 4,
                question: "How do I know if my account is active?",
                answer: "You'll see a green dot next to your name in the top-right corner when your account is active.",
        },
    ];

    const filteredEntries = entries.filter((entry) =>
    entry.question.toLowerCase().includes(searchPrompt.toLowerCase())
    );


    return(
        <div>
            <Navbar />
            <div className = "faq-container">
                
                <div className = "faq-heading">
                    <div className ="faq-eyebrow"> FAQs </div>
                    <h1 className = "faq-title"> Frequently Asked Questions</h1>
                </div>

                <SearchBar 
                    searchPrompt = {searchPrompt} 
                    onSearchChange = {setSearchPrompt} 
                    entries = {entries}/>

                <h2 className = "faq-subheading"> Top FAQ's</h2>
  
                {/* placeholder */}
                {filteredEntries.length === 0 ? (
                <div className="database-empty">No matching questions found.</div>
                ) : (
                <div className="database">
                    {filteredEntries.map((entry) => (
                    <FaqItem key={entry.id} question={entry.question} answer={entry.answer} />
                    ))}
                </div>
                )}

            </div>
        </div>
    )
}