
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        #resources .card-title {
            border-bottom: 1px solid #d2d2d2;
            margin-bottom: 10px;
            padding-bottom: 10px;
        }
        
        .file-type-indicator .fas {
            font-size: 1em;
        }
        
        .file-type-indicator {
            font-size: 12px;
            border: 1px solid #d2d2d2;
            display: inline-block;
            padding: 7px 21px;
            border-radius: 3px;
            background: #e6e6e6;
            font-family: 'creato_bold';
        }
        
        .file-type-indicator i {
            margin-right: 6px;
        }
        
        .bg-primaryy {
            background-color: rgb(97 118 156) !important;
        }

        .bg-successs {
            background-color: rgb(0 32 86) !important;
        }

        .filter-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .filter-title { 
            font-weight: 600; 
            color: #495057; 
            margin-bottom: 12px; 
        }
        
        .form-check { 
            margin-bottom: 8px; 
        }
        
        .form-check-input:checked { 
            background-color: #0d6efd; 
            border-color: #0d6efd; 
        }
        
        .search-box { 
            position: relative; 
        }
        
        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .search-input { 
            padding-left: 40px; 
        }
        
        .file-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            background-color: white;
        }
        
        .file-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .card-title { 
            color: #212529; 
            font-weight: 600; 
            font-size: 1.1rem; 
            margin-bottom: 15px; 
        }
        
        .badge-container { 
            margin-bottom: 15px; 
        }
        
        .file-type-indicator {
            font-size: 0.9rem;
        }
        
        .file-type-indicator i {
            margin-right: 6px;
        }
        
        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .reset-filters { 
            margin-top: 15px; 
        }
        
        .results-count {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        
        .main-content-container {
            max-height: 1000px;
            overflow-y: auto;
            padding-right: 15px;
        }
        
        .main-content-container::-webkit-scrollbar { 
            width: 8px; 
        }
        
        .main-content-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .main-content-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .main-content-container::-webkit-scrollbar-thumb:hover { 
            background: #a8a8a8; 
        }

        /* Google Sheets Integration Styles */
        .loading-spinner {
            text-align: center;
            padding: 40px;
            display: none;
        }
        
        .error-message {
            display: none;
            margin-bottom: 20px;
        }

        .badge {
            transition: background-color 0.3s ease, color 0.3s ease, border 0.3s ease;
        }

        .badge-active-filter {
            background-color: #fff !important;
            color: #ee3a43 !important;
            border: 1.5px solid #ee3a43;
        }

        .badge-active-filter-resource {
            background-color: #fff !important;
            color: #61769c !important;
            border: 1.5px solid #61769c;
        }

        .filter-help {
            font-size: 0.72em;
        }
    </style>
</head>
<body>

<div class="container-fluid py-4" id="resources">
    <div class="container">
        <div class="text-center mb-4">
            <h1><i style="font-size: 32px; color: #61769c;" class="fas fa-folder-open me-2"></i>Board Resource Center</h1>
        </div>



        <!-- Loading spinner -->
        <div class="loading-spinner" id="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading data from Google Sheets...</p>
        </div>
        
        <!-- Error message -->
        <div class="error-message alert alert-danger" id="error-message" role="alert">
            <h4 class="alert-heading">Error Loading Data</h4>
            <p class="mb-0">Failed to load data from Google Sheets. Please check your internet connection and try again.</p>
            <hr>
            <button class="btn btn-outline-danger" onclick="loadSheetData()">Retry</button>
        </div>

        <div class="row">
            <!-- Filters Sidebar -->
            <div class="col-lg-3 col-md-4">
                <div class="filter-section">
                    <h5 class="mb-4"><i style="font-size: 20px; color: #61769c;" class="fas fa-filter me-2"></i>Filters</h5>

                    <!-- Search -->
                    <div class="filter-group mb-3">
                        <div class="filter-title">Search</div>
                        <div class="search-box">
                            <input type="text" class="form-control search-input" id="searchInput" placeholder="Search files...">
                        </div>
                    </div>

                    <!-- AO Type Filter -->
                    <div class="filter-group mb-3">
                        <div class="filter-title">AO Type</div>
                        <small class="d-block mb-2 text-muted filter-help">Shown as <span class="badge bg-successs">dark blue</span></small>
                        <small class="d-block mb-2 text-muted filter-help">A resource can be tagged with multiple AO Types. Your active filter is displayed with a <span style="background:#fff;color:#ee3a43;border:1.5px solid #ee3a43;padding:1px 5px;border-radius:4px;font-weight:600;">white tag with red text</span>.</small>
                        <div id="aoTypeFilters">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="aoType" id="aoAll" value="all" checked>
                                <label class="form-check-label" for="aoAll">All Types</label>
                            </div>
                            <!-- Dynamic filters will be added here -->
                        </div>
                    </div>

                    <!-- Resource Type Filter -->
                    <div class="filter-group">
                        <div class="filter-title">Resource Type</div>
                        <small class="d-block mb-2 text-muted filter-help">Shown as <span class="badge bg-primaryy">light blue</span></small>
                        <small class="d-block mb-2 text-muted filter-help">A resource can be tagged with multiple Resource Types. Your active filter is displayed with a <span style="background:#fff;color:#61769c;border:1.5px solid #61769c;padding:1px 5px;border-radius:4px;font-weight:600;">light blue outlined tag</span>.</small>
                        <div id="resourceTypeFilters">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="resourceType" id="resourceAll" value="all" checked>
                                <label class="form-check-label" for="resourceAll">All Types</label>
                            </div>
                            <!-- Dynamic filters will be added here -->
                        </div>
                    </div>

                    <!-- Reset Button -->
                    <div class="reset-filters">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="resetFilters">
                            <i style="font-size: 20px;" class="fas fa-undo me-2"></i>Reset Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">
                <div class="main-content-container">
                    <div class="results-count" id="resultsCount"></div>
                    <div class="row" id="fileContainer"></div>
                    <div class="no-results d-none" id="noResults">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h4>No files found</h4>
                        <p>Try adjusting your filters or search terms.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // =============================================
    // CONFIGURATION - UPDATE THESE VALUES
    // =============================================
    const SPREADSHEET_ID = '1gfD5PsDnv_-LHbRpBZzyNDJZlTDLXdgaHE7uvMDVJlE';
    const API_KEY = 'AIzaSyAPM5xbd3Lxk6dRx_5Kb9U2fUzdUEiwUzw'; // Replace with your actual API key
    const SHEET_RANGE = 'Sheet1!A:Z'; // Adjust range as needed
    // =============================================

    let filesData = []; // Will be populated from Google Sheets

    // Process Google Sheets API response
    function processSheetData(response) {
        const values = response.values;
        if (!values || values.length === 0) {
            throw new Error('No data found in the spreadsheet');
        }

        // First row contains headers
        const headers = values[0];
        const data = [];

        console.log('Headers found:', headers); // Debug log

        // Process each row (skip header row)
        for (let i = 1; i < values.length; i++) {
            const row = values[i];
            if (row.length === 0) continue; // Skip empty rows
            
            const rowData = {};
            headers.forEach((header, index) => {
                rowData[header] = row[index] || ''; // Handle missing values
            });
            
            // Only add rows that have required fields (at minimum a title)
            if (rowData.title || rowData.Title) {
                data.push(rowData);
            }
        }

        return data;
    }

    // Function to load data from Google Sheets
    async function loadSheetData() {
        const loadingSpinner = document.getElementById('loading-spinner');
        const errorMessage = document.getElementById('error-message');

        // Show loading
        loadingSpinner.style.display = 'block';
        errorMessage.style.display = 'none';

        try {
            // Validate configuration
            if (API_KEY === 'YOUR_API_KEY_HERE' || !API_KEY) {
                throw new Error('API key not configured. Please update the API_KEY constant in the code.');
            }

            // Build the Google Sheets API URL
            const url = `https://sheets.googleapis.com/v4/spreadsheets/${SPREADSHEET_ID}/values/${SHEET_RANGE}?key=${API_KEY}`;
            
            const response = await fetch(url);
            
            if (!response.ok) {
                if (response.status === 403) {
                    throw new Error('Access denied. Please check API key permissions and ensure the spreadsheet is accessible.');
                } else if (response.status === 404) {
                    throw new Error('Spreadsheet not found. Please verify the spreadsheet ID and ensure it exists.');
                } else {
                    const errorData = await response.json().catch(() => null);
                    const errorMessage = errorData?.error?.message || `${response.status} ${response.statusText}`;
                    throw new Error(`Failed to load data: ${errorMessage}`);
                }
            }
            
            const jsonData = await response.json();
            const data = processSheetData(jsonData);

            // Hide loading
            loadingSpinner.style.display = 'none';

            if (data.length === 0) {
                throw new Error('No data found in Google Sheet');
            }

            processLoadedData(data);

        } catch (error) {
            console.error('Failed to load sheet data:', error);
            loadingSpinner.style.display = 'none';
            errorMessage.style.display = 'block';
            errorMessage.querySelector('p').textContent = error.message;
        }
    }

    // Process loaded data and transform to expected format
    function processLoadedData(data) {
        // Transform Google Sheets data to match expected format
        filesData = data.map((row, index) => {
            console.log('Raw row data:', row); // Debug log
            console.log('Available keys:', Object.keys(row)); // Show all column names
            
            // Handle different possible column name variations
            const getColumnValue = (variations) => {
                for (let variation of variations) {
                    if (row[variation] !== undefined && row[variation] !== '') {
                        return row[variation].toString().trim();
                    }
                }
                return '';
            };

            const transformedRow = {
                id: row.id || (index + 1).toString(),
                title: getColumnValue(['title', 'Title', 'Name', 'name']) || 'Untitled File',
                url: getColumnValue(['url', 'URL', 'link', 'Link']) || '#',
                'Resource Type': (getColumnValue(['Resource Type', 'resourceType', 'resource_type']) || 'Uncategorized').split(',').map(s => s.trim()).filter(Boolean),
                'AO Type': (getColumnValue(['AO Type', 'aoType', 'ao_type']) || 'General').split(',').map(s => s.trim()).filter(Boolean),
                'File Type': getColumnValue(['File Type', 'fileType', 'file_type']) || 'Unknown',
                'Meta Data': getColumnValue(['Meta Data', 'metaData', 'meta_data', 'description', 'Description']) || ''
            };
            
            console.log('Transformed row:', transformedRow);
            return transformedRow;
        });
        
        console.log('Final filesData:', filesData); // Debug log

        // Build dynamic filters and display files
        buildDynamicFilters();
        filterFiles();
        
        // Re-attach search event listener after data loads
        const searchInput = document.getElementById('searchInput');
        searchInput.removeEventListener('input', filterFiles); // Remove any existing listener
        searchInput.addEventListener('input', filterFiles);
    }

    // Build dynamic filter options based on data
    function buildDynamicFilters() {
        const resourceTypes = [...new Set(filesData.flatMap(file => file['Resource Type']))].sort();
        const aoTypes = [...new Set(filesData.flatMap(file => file['AO Type']))].filter(t => t.toLowerCase() !== 'all types').sort();

        // Build Resource Type filters
        const resourceTypeContainer = document.getElementById('resourceTypeFilters');
        const existingResourceAll = resourceTypeContainer.querySelector('#resourceAll').parentElement;
        resourceTypeContainer.innerHTML = '';
        resourceTypeContainer.appendChild(existingResourceAll);

        resourceTypes.forEach((type, index) => {
            const id = `resource${index}`;
            const filterHtml = `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="resourceType" id="${id}" value="${type}">
                    <label class="form-check-label" for="${id}">${type}</label>
                </div>
            `;
            resourceTypeContainer.insertAdjacentHTML('beforeend', filterHtml);
        });

        // Build AO Type filters
        const aoTypeContainer = document.getElementById('aoTypeFilters');
        const existingAoAll = aoTypeContainer.querySelector('#aoAll').parentElement;
        aoTypeContainer.innerHTML = '';
        aoTypeContainer.appendChild(existingAoAll);

        aoTypes.forEach((type, index) => {
            const id = `ao${index}`;
            const filterHtml = `
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="aoType" id="${id}" value="${type}">
                    <label class="form-check-label" for="${id}">${type}</label>
                </div>
            `;
            aoTypeContainer.insertAdjacentHTML('beforeend', filterHtml);
        });

        // Re-attach event listeners
        document.querySelectorAll('input[name="resourceType"]').forEach(r => r.addEventListener('change', filterFiles));
        document.querySelectorAll('input[name="aoType"]').forEach(r => r.addEventListener('change', filterFiles));
    }

    function getFileTypeIcon(fileType) {
        console.log('File type received:', fileType); // Debug log
        
        if (!fileType || fileType === 'Unknown') {
            return '<i class="fas fa-file text-muted"></i> Unknown';
        }
        
        const type = fileType.trim();
        
        // Exact matches for your specific file types
        switch (type) {
            case 'PDF':
                return '<i class="fas fa-file-pdf text-danger"></i> PDF';
            case 'Word Document':
                return '<i class="fas fa-file-word text-primary"></i> Word Document';
            case 'Spreadsheet':
                return '<i class="fas fa-file-excel text-success"></i> Spreadsheet';
            case 'Image':
                return '<i class="fas fa-file-image text-info"></i> Image';
            default:
                // Fallback with partial matching
                const lowerType = type.toLowerCase();
                if (lowerType.includes('pdf')) {
                    return '<i class="fas fa-file-pdf text-danger"></i> PDF';
                } else if (lowerType.includes('word') || lowerType.includes('doc')) {
                    return '<i class="fas fa-file-word text-primary"></i> Word';
                } else if (lowerType.includes('spreadsheet') || lowerType.includes('excel')) {
                    return '<i class="fas fa-file-excel text-success"></i> Spreadsheet';
                } else if (lowerType.includes('image')) {
                    return '<i class="fas fa-file-image text-info"></i> Image';
                } else {
                    return `<i class="fas fa-file text-muted"></i> ${type}`;
                }
        }
    }

    function getBadgeColor(type) {
        return type === 'Resource Type' ? 'bg-primaryy' : type === 'AO Type' ? 'bg-successs' : 'bg-secondary';
    }

    function createFileCard(file) {
        const downloadUrl = file.url || '#';
        const selectedAoType = document.querySelector('input[name="aoType"]:checked')?.value || 'all';
        const selectedResourceType = document.querySelector('input[name="resourceType"]:checked')?.value || 'all';

        return `
            <div class="col-lg-6 col-xl-4 mb-3">
                <div class="card file-card h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">${file.title}</h5>
                            <div class="badge-container mb-2">
                                ${file['Resource Type'].map(t => `<span class="badge ${getBadgeColor('Resource Type')} me-1${selectedResourceType !== 'all' && t === selectedResourceType ? ' badge-active-filter-resource' : ''}">${t}</span>`).join('')}
                                ${file['AO Type'].map(t => `<span class="badge ${getBadgeColor('AO Type')} me-1${selectedAoType !== 'all' && t === selectedAoType ? ' badge-active-filter' : ''}">${t}</span>`).join('')}
                            </div>
                            <div class="file-type-indicator mb-3">${getFileTypeIcon(file['File Type'])}</div>
                        </div>
                        <a href="${downloadUrl}" target="_blank" class="btn btn-primary w-100 mt-auto">
                            <i style="font-size: 1em" class="fas fa-download me-2"></i>Download File
                        </a>
                    </div>
                </div>
            </div>
        `;
    }

    function filterFiles() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const selectedResourceType = document.querySelector('input[name="resourceType"]:checked')?.value || 'all';
        const selectedAoType = document.querySelector('input[name="aoType"]:checked')?.value || 'all';

        console.log('Search term:', searchTerm); // Debug log

        const filteredFiles = filesData.filter(file => {
            console.log('Checking file:', file.title, 'Meta Data:', JSON.stringify(file['Meta Data'])); // Debug log
            
            // Search through title and meta data
            const titleMatch = file.title.toLowerCase().includes(searchTerm);
            const metaMatch = file['Meta Data'] && file['Meta Data'].toLowerCase().includes(searchTerm);
            const matchesSearch = searchTerm === '' || titleMatch || metaMatch;
            
            console.log('Title match:', titleMatch, 'Meta match:', metaMatch, 'Overall match:', matchesSearch); // Debug log

            const matchesResourceType = selectedResourceType === 'all' || file['Resource Type'].includes(selectedResourceType);
            const matchesAoType = selectedAoType === 'all' || file['AO Type'].includes(selectedAoType);

            return matchesSearch && matchesResourceType && matchesAoType;
        });

        console.log('Filtered files count:', filteredFiles.length); // Debug log
        displayFiles(filteredFiles);
    }

    function displayFiles(files) {
        const resultsCount = document.getElementById('resultsCount');
        const fileContainer = document.getElementById('fileContainer');
        const noResults = document.getElementById('noResults');

        resultsCount.textContent = `Showing ${files.length} file${files.length !== 1 ? 's' : ''}`;
        if (files.length === 0) {
            fileContainer.innerHTML = '';
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
            fileContainer.innerHTML = files.map(createFileCard).join('');
        }
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('resourceAll').checked = true;
        document.getElementById('aoAll').checked = true;
        filterFiles();
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Load data from Google Sheets
        loadSheetData();
        
        // Add event listeners
        document.getElementById('resetFilters').addEventListener('click', resetFilters);
    });
</script>
