import '../../css/autocomplete.css';

export default function Autocomplete({ suggestions, onSelect }) {
  if (suggestions.length === 0) {
    return null;
  }

  return (
    <div className="autocomplete-list">
      {suggestions.map((entry) => (
        <div
          key={entry.id}
          className="autocomplete-item"
          onClick={() => onSelect(entry.question)}
        >
          {entry.question}
        </div>
      ))}
    </div>
  );
}