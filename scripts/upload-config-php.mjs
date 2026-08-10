import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const example = fs.readFileSync(path.join(root, 'public', 'api', 'config.example.php'), 'utf8');
const remote = `${sftpConfig.remotePath}/api/config.php`;

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });
  await sftp.put(Buffer.from(example, 'utf8'), remote);
  console.log(`Wrote ${remote}`);
} finally {
  await sftp.end();
}