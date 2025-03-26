import React from 'react';
import ReactDOM from 'react-dom';

const App = () => {
    return (
        <div>
            <h1>Hello from React!</h1>
        </div>
    );
};

document.addEventListener('DOMContentLoaded', () => {
    const rootElement = document.getElementById('shopxpert-react-app');
    if (rootElement) {
        ReactDOM.render(<App />, rootElement);
    }
});
