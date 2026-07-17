const fs = require('fs');
const path = require('path');

function replaceInDir(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            replaceInDir(fullPath);
        } else if (fullPath.endsWith('.blade.php')) {
            const content = fs.readFileSync(fullPath, 'utf8');
            if (content.includes('teal-')) {
                const newContent = content.replace(/teal-/g, 'blue-');
                fs.writeFileSync(fullPath, newContent, 'utf8');
                console.log('Updated', fullPath);
            }
        }
    }
}

replaceInDir(path.join(__dirname, 'resources', 'views'));
