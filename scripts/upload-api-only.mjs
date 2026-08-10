import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const remoteApi = `${sftpConfig.remotePath}/api`;
const files = ['work-request.php', 'mail-send.php', 'config.example.php'];

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });
  for (const file of files) {
    const local = path.join(root, 'public', 'api', file);
    await sftp.put(local, `${remoteApi}/${file}`);
    console.log(`UPLOADED ${file}`);
  }
  const config = fs.readFileSync(path.join(root, 'public', 'api', 'config.example.php'), 'utf8');
  await sftp.put(Buffer.from(config, 'utf8'), `${remoteApi}/config.php`);
  console.log('UPLOADED config.php');
} finally {
  await sftp.end();
}