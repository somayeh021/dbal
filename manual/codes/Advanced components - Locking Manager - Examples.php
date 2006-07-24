At the page where the lock is requested...
<?php

// Get a locking manager instance
$lockingMngr = new Doctrine_Locking_Manager_Pessimistic();

try
{
    // Ensure that old locks which timed out are released before we try to acquire our lock
    $lockingMngr->releaseAgedLocks(300); // 300 seconds = 5 minutes timeout

    // Try to get the lock on a record
    $gotLock = $lockingMngr->getLock(
                       $myRecordToLock, // The record to lock. This can be any Doctrine_Record
                       'Bart Simpson'   // The unique identifier of the user who is trying to get the lock
               );

    if($gotLock)
    {
        echo "Got lock!";
        // ... proceed
    }
    else
    {
        echo "Sorry, someone else is currently working on this record";
    }
}
catch(Doctrine_Locking_Exception $dle)
{
    echo $dle->getMessage();
    // handle the error
}

?>

At the page where the transaction finishes...
<?php
// Get a locking manager instance
$lockingMngr = new Doctrine_Locking_Manager_Pessimistic();

try
{
    if($lockingMngr->releaseLock($myRecordToUnlock, 'Bart Simpson'))
    {
        echo "Lock released";
    }
    else
    {
        echo "Record was not locked. No locks released.";
    }
}
catch(Doctrine_Locking_Exception $dle)
{
    echo $dle->getMessage();
    // handle the error
}
?>
