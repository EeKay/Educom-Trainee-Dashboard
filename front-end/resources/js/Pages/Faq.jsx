import {useState, useEffect} from 'react';
import Navbar from '../Components/Navbar';
import SearchBar from '../Components/SearchBar';
import FaqItem from '../Components/FaqItem';
import ChatBot from '../Components/ChatBot';
import '../../css/faq.css';

export default function Faq(props){

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
                    {filteredFaqs.map((faq) => {
                        if (faq.is_active == true){
                            return <FaqItem key={faq.id} question={faq.question} answer={faq.answer} />
                        };
                    })}

                </div>
                )}
                <ChatBot/>
            </div>
        </div>
    )
}
