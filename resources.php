ID<div class="container">
    <div class="container" id="resources">
	<div class="container">
		<h1 class="text-center my-5">Resource Finder</h1>
		<!-- Resource Type Selection -->
		
<div class="row">
			<div class="col-md-4 mb-3">
				<div class="radio-group h-100">
					<strong><i class="fa-solid fa-screwdriver-wrench"></i> Select Resource Type:</strong> <label><input checked="checked" name="resource" type="radio" value="all"> All</label> <label><input name="resource" type="radio" value="best-practices"> Best Practices</label> <label><input name="resource" type="radio" value="dev-tools"> Development Tools</label> <label><input name="resource" type="radio" value="branding"> Branding and Marketing Resources</label>
				</div>
			</div>

			<div class="col-md-4 mb-3">
				<div class="radio-group h-100 ">
					<strong><i class="fa-solid fa-user"></i> Select User Role:</strong> <label><input checked="checked" name="role" type="radio" value="all"> All</label> <label><input name="role" type="radio" value="staff"> Staff</label> <label><input name="role" type="radio" value="officer"> Officer</label> <label><input name="role" type="radio" value="volunteer"> Volunteer</label>
				</div>
			</div>
				<div class="col-md-4 mb-3">
			<div class="search-group h-100">
			<strong><i class="fa-solid fa-magnifying-glass"></i> Search Resources:</strong> <input id="search-input" placeholder="Search by document name or tags" style="width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px;" type="text">
		</div>
			</div>
		</div>
		<!-- Search Field -->

		<!-- Resource Items Container -->
<div class="resource-wrapper" id="resource-container">
			<!-- Resources will be populated here via JavaScript -->
		</div>
	</div>
</div>
<script>
        // JSON data for resources
        const resourcesData = [
            {
                "id": 1,
                "title": "Best Practices 2020",
                "url": "#",
                "primaryTag": "best-practices",
                "secondaryTags": []
            },
            {
                "id": 2,
                "title": "Best Practices 2016",
                "url": "#",
                "primaryTag": "best-practices",
                "secondaryTags": []
            },
            {
                "id": 3,
                "title": "Best Practices 2011",
                "url": "#",
                "primaryTag": "best-practices",
                "secondaryTags": []
            },
            {
                "id": 4,
                "title": "Manual of Advisory Organizations and Articles of Organization",
                "url": "#",
                "primaryTag": "dev-tools",
                "secondaryTags": ["officer"]
            },
            {
                "id": 5,
                "title": "Officer's Commitment in Working with Advisory Boards",
                "url": "#",
                "primaryTag": "dev-tools",
                "secondaryTags": ["officer"]
            },
            {
                "id": 6,
                "title": "The Manifestio",
                "url": "#",
                "primaryTag": "branding",
                "secondaryTags": ["officer"]
            },
            {
                "id": 7,
                "title": "Doing the Most Good Presentation Template",
                "url": "#",
                "primaryTag": "branding",
                "secondaryTags": ["volunteer"]
            },
            {
                "id": 8,
                "title": "Volunteer Best Practices Example Tile",
                "url": "#",
                "primaryTag": "best-practices",
                "secondaryTags": ["volunteer"]
            },
            {
                "id": 9,
                "title": "Staff Best Practices Example Tile",
                "url": "#",
                "primaryTag": "best-practices",
                "secondaryTags": ["staff"]
            }
        ];

        document.addEventListener('DOMContentLoaded', function () {
            const resourceContainer = document.getElementById('resource-container');
            
            // Function to render resources
            function renderResources(resources) {
                resourceContainer.innerHTML = '';
                resources.forEach(resource => {
                    const resourceDiv = document.createElement('div');
                    resourceDiv.className = 'resource-item';
                    resourceDiv.setAttribute('data-id', resource.id);
                    resourceDiv.setAttribute('data-primary-tag', resource.primaryTag);
                    resourceDiv.setAttribute('data-secondary-tags', resource.secondaryTags.join(','));
                    
                    const tagsHTML = resource.secondaryTags.length > 0 
                        ? `<div class="resource-tags">
                             <span class="tag">${resource.primaryTag}</span>
                             ${resource.secondaryTags.map(tag => `<span class="tag">${tag}</span>`).join('')}
                           </div>`
                        : `<div class="resource-tags"><span class="tag">${resource.primaryTag}</span></div>`;
                    
                    resourceDiv.innerHTML = `
                        <a href="${resource.url}"><i class="fa-solid fa-square-up-right"></i> ${resource.title}</a>
                        ${tagsHTML}
                    `;
                    
                    resourceContainer.appendChild(resourceDiv);
                });
            }
            
            // Function to update display based on filters
            function updateDisplay() {
                const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
                const selectedResource = document.querySelector('input[name="resource"]:checked')?.value;
                const searchTerm = document.getElementById('search-input').value.toLowerCase().trim();
                const resourceItems = document.querySelectorAll('.resource-item');
                
                resourceItems.forEach(item => {
                    const itemSecondaryTags = item.getAttribute('data-secondary-tags').split(',').filter(tag => tag !== '');
                    const itemPrimaryTag = item.getAttribute('data-primary-tag');
                    const itemTitle = item.querySelector('a').textContent.toLowerCase();
                    
                    // Role matching: if "all" is selected, or if secondaryTags includes the selected role, or if secondaryTags is empty (universal)
                    const matchesRole = selectedRole === 'all' || 
                                       itemSecondaryTags.includes(selectedRole) || 
                                       itemSecondaryTags.length === 0;
                    
                    const matchesResource = selectedResource === 'all' || itemPrimaryTag === selectedResource;
                    
                    // Search matching: check if search term appears in title, primary tag, or secondary tags
                    const matchesSearch = searchTerm === '' || 
                                         itemTitle.includes(searchTerm) ||
                                         itemPrimaryTag.toLowerCase().includes(searchTerm) ||
                                         itemSecondaryTags.some(tag => tag.toLowerCase().includes(searchTerm));
                    
                    if (matchesRole && matchesResource && matchesSearch) {
                        item.classList.add('visible');
                    } else {
                        item.classList.remove('visible');
                    }
                });
            }
            
            // Initialize the display
            renderResources(resourcesData);
            
            // Add event listeners to radio buttons and search input
            document.querySelectorAll('input[name="role"], input[name="resource"]').forEach(input => {
                input.addEventListener('change', updateDisplay);
            });
            
            document.getElementById('search-input').addEventListener('input', updateDisplay);
            
            // Initial display update
            updateDisplay();
        });
    </script>
</div>
