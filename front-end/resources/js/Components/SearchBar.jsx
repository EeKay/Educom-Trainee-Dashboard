import '../../css/search-bar.css';
import Autocomplete from './Autocomplete';

export default function SearchBar ({searchPrompt, onSearchChange, entries}){
    const suggestions = 
        searchPrompt.length > 0
        ? entries.filter((entry) =>
        entry.question.toLowerCase().includes(searchPrompt.toLowerCase())
        ) : [];

    const showSuggestions = !entries.some((entry) => entry.question === searchPrompt);

    return(
        <div className="autocomplete-container">
        <div className="search-bar">
            <input
            type="text"
            placeholder="Search"
            value={searchPrompt}
            onChange={(e) => onSearchChange(e.target.value)}
            className="search-bar-input"
            />
        </div>
        {showSuggestions && (
        <Autocomplete suggestions={suggestions} onSelect={onSearchChange} />
        )}
        </div>
    );
}