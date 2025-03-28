import { useState } from 'react';

export function Import() {
    const [file, setFile] = useState(null);
    const [message, setMessage] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('import_csv', file);

        try {
            const response = await fetch('/api/import', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            setMessage(data.message || 'File uploaded successfully');
        } catch (error) {
            console.error('Upload error:', error);
            setMessage('Error uploading file: ' + error.message);
        }
    };

    return (
        <div className="container mt-4">
            {message && (
                <div className="alert alert-success">
                    {message}
                </div>
            )}
            <form onSubmit={handleSubmit}>
                <div className="input-group mb-3">
                    <input 
                        type="file" 
                        className="form-control" 
                        onChange={(e) => setFile(e.target.files[0])}
                        accept=".csv"
                    />
                </div>
                <button type="submit" className="btn btn-success">
                    Import CSV
                </button>
            </form>
        </div>
    );
} 