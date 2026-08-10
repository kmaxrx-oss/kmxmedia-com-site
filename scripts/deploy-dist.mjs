import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import SftpClient from 'ssh2-sftp-client';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const root = path.resolve(__dirname, '..');
const sftpConfig = JSON.parse(fs.readFileSync(path.join(root, '.vscode', 'sftp.json'), 'utf8'));
const localDist = path.join(root, 'dist');
const remoteBase = sftpConfig.remotePath;

async function uploadDir(sftp, local, remote) {
  const entries = fs.readdirSync(local, { withFileTypes: true });
  await sftp.mkdir(remote, true);
  for (const entry of entries) {
    const localPath = path.join(local, entry.name);
    const remotePath = `${remote}/${entry.name}`;
    if (entry.isDirectory()) {
      await uploadDir(sftp, localPath, remotePath);
    } else {
      await sftp.put(localPath, remotePath);
      console.log(`UPLOADED ${entry.name} -> ${remotePath}`);
    }
  }
}

const sftp = new SftpClient();
try {
  await sftp.connect({
    host: sftpConfig.host,
    port: sftpConfig.port,
    username: sftpConfig.username,
    password: sftpConfig.password,
  });
  console.log(`Connected. Uploading ${localDist} -> ${remoteBase}`);
  await uploadDir(sftp, localDist, remoteBase);
  console.log('Deploy complete.');
} finally {
  await sftp.end();
}