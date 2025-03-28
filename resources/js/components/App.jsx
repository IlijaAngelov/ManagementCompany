import { Routes, Route } from 'react-router-dom';
import { NavigationBar } from './NavigationBar';
import { Import } from './Import';
// import { Home } from './Home';

function App() {
    // console.log('App component rendering');
    return (
        <div className="app">
            <NavigationBar />
            <main>
                <Routes>
                    <Route path="/" element={<div>Home Page</div>} />
                    <Route path="/import" element={<Import />} />
                </Routes>
            </main>
        </div>
    );
}

export default App; 