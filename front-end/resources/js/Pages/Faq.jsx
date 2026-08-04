import {useState, useEffect} from 'react';
import Navbar from '../Components/Navbar';
import SearchBar from '../Components/SearchBar';
import FaqItem from '../Components/FaqItem';
import ChatBot from '../Components/ChatBot';
import '../../css/faq.css';

export default function Faq(props){
    // const [faqs, setFaqs] = useState ([]);
    const currentUser = 2; //hardcoded for now, but will be changed to token from the session 

    const [searchPrompt, setSearchPrompt] = useState('');
    const faqs = props.faqs;


    const filteredFaqs = faqs.filter((faq) =>
    faq.question.toLowerCase().includes(searchPrompt.toLowerCase())
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
                    faqs = {faqs}/>

                <h2 className = "faq-subheading"> Top FAQ's</h2>
  
                
                {filteredFaqs.length === 0 ? (
                <div className="database-empty">No matching questions found.</div>
                ) : (
                <div className="database">
                    {filteredFaqs.map((faq) => (
                    <FaqItem question={faq.question} answer={faq.answer} />
                    ))}
                </div>
                )}
                <ChatBot/>
            </div>
        </div>
    )
}

    // useEffect(() => {
    // fetch(`${API_BASE}/faq`, {
    //     // method: 'POST',
    //     // body: JSON.stringify({question: 'hoe gaat het?', answer:'goed'})
    // })
    //     .then((res) => res.json())
    //     .then(allFaqs => {
    //         const formatted = allFaqs.map((faq) => ({ 
    //             question: faq.question,
    //             answer: faq.answer,
    //             id: faq.id
    //         }));
    //         setFaqs(formatted)
    //     })
    //     .catch((err) => console.error('Error fetching faqs:', err));
    // }, []);
