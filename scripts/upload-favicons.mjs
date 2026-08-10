import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const remoteBase = sftpConfig.remotePath;

const uploads = [
  ['public/favicon.png', 'favicon.png'],
  ['public/favicon.ico', 'favicon.ico'],
  ['public/apple-touch-icon.png', 'apple-touch-icon.png'],
  ['dist/index.html', 'index.html'],
  ['dist/work-request/index.html', 'work-request/index.html'],
  ['dist/work-request/thanks/index.html', 'work-request/thanks/index.html'],
];

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });

  for (const [localRel, remoteRel] of uploads) {
    const localPath = path.join(root, localRel);
    const remotePath = `${remoteBase}/${remoteRel}`;
    await sftp.mkdir(path.posix.dirname(remotePath), true);
    await sftp.put(localPath, remotePath);
    console.log(`UPLOADED ${localRel} -> ${remotePath}`);
  }
} finally {
  await sftp.end();
}