import {useState} from 'react';

export function Import() {
    const [file, setFile] = useState(null);
    const [message, setMessage] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('import_csv', file);

        try {
            const response = await fetch('/import', {
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

    return (<>
            <div className="grid w-3/4 mx-auto container mt-4">
                <div>
                    <form onSubmit={handleSubmit}>
                        <div>
                            <h2 className="text-center font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight pb-2">
                                Import
                            </h2>
                            {message && (
                                <div className="alert alert-success">
                                    {message}
                                </div>
                            )}
                        </div>

                        <div className="fields flex items-center justify-center font-bold py-2 px-4 gap-2">
                            <div className="">
                                <input
                                    id="import_csv"
                                    type="file"
                                    className="h-10 file:h-10 file:px-3 file:py-2 bg-gray-100"
                                    onChange={(e) => setFile(e.target.files[0])}
                                    accept=".csv"
                                />
                            </div>
                            <div className="">
                                <button type="submit"
                                        className="bg-blue-500 hover:bg-blue-700 text-white font-bold h-10 px-4 rounded">
                                    Import CSV
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
